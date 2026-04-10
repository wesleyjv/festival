<?php

namespace App\Controllers;

use App\Security\Csrf;
use App\Services\ContentService;
use App\Services\AudioUploadService;
use App\Services\ImageUploadService;
use App\Services\Validator;
use App\Config\CmsPageDefinitions;
use App\Config\FestivalEventConfig;
use App\Repositories\UserRepository;
use App\Repositories\EventRepository;
use App\Repositories\HistoryTourRepository;
use App\Repositories\StoryEventRepository;
use App\Repositories\YummyRestaurantAdminRepository;
use App\Services\AdminYummyService;

class AdminController
{
    use HandlesControllerErrors;

    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

   

    public function dashboard($vars = [])
    {
        try {
        $this->requireAdmin();

        $contentService = new ContentService();
        $cmsPages = CmsPageDefinitions::pages();
        $contentByPage = [];
        foreach (CmsPageDefinitions::jsonPageKeys() as $pageKey) {
            $contentByPage[$pageKey] = $contentService->getPageContent($pageKey);
        }
        $jazzContent = $contentService->getPageContent('jazz');

        $jazzCmsArtists = (new EventRepository())->getJazzEvents(null);
        $jazzArtistContents = [];
        foreach ($jazzCmsArtists as $jazzArtist) {
            $jazzArtistContents[$jazzArtist->eventId] = $contentService->getPageContent(
                'jazz_' . $jazzArtist->eventId
            );
        }

        $userSearch = $_GET['search'] ?? '';
        $userRole   = $_GET['role']   ?? '';
        $userSort   = $_GET['sort']   ?? 'id';
        $userDir    = $_GET['dir']    ?? 'ASC';
        $users      = $this->userRepo->getAllUsers($userSearch, $userRole, $userSort, $userDir);
        $totalUsers = $this->userRepo->countAll();
        $userError  = $_GET['user_error'] ?? '';
        $userSaved  = $_GET['user_saved'] ?? '';
        $jazzArtistError   = isset($_GET['jazz_error']) ? (string) $_GET['jazz_error'] : '';
        $jazzArtistNotice  = isset($_GET['jazz_notice']) ? (string) $_GET['jazz_notice'] : '';

        // Events management (Story, Yummy, History, Jazz)
        $storyRepo    = new StoryEventRepository();
        $storyEvents  = $storyRepo->getAllForAdmin();
        $storyError   = $_GET['story_error'] ?? '';
        $storySaved   = $_GET['story_saved'] ?? '';

        $yummyRestaurants = (new AdminYummyService())->findAllRestaurantsForAdmin();
        $yummyError       = isset($_GET['yummy_error']) ? (string) $_GET['yummy_error'] : '';
        $yummySaved       = isset($_GET['yummy_saved']) ? (string) $_GET['yummy_saved'] : '';

        $historyTourRepo = new HistoryTourRepository();
        $historyTours    = $historyTourRepo->getAllForAdmin();
        $historyError    = isset($_GET['history_error']) ? (string) $_GET['history_error'] : '';
        $historySaved    = isset($_GET['history_saved']) ? (string) $_GET['history_saved'] : '';

        $eventsTab = strtolower(trim((string) ($_GET['events_tab'] ?? '')));
        if (!in_array($eventsTab, ['story', 'yummy', 'history', 'jazz'], true)) {
            $eventsTab = 'story';
        }

        $festivalYummyEventId = FestivalEventConfig::yummyEventId();

        require __DIR__ . '/../views/admin/dashboard.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    

    public function saveContent($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?error=csrf#content');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin');
            exit;
        }

        $page = $_POST['page'] ?? '';
        if ($page === '') {
            header('Location: /admin?error=missing_page#content');
            exit;
        }

        $data = $_POST;
        unset($data['page'], $data[Csrf::FIELD_NAME]);

        $tracksJson = (string) ($data['tracks_json'] ?? '');
        $imagesJson = (string) ($data['images_json'] ?? '');
        unset($data['tracks_json'], $data['images_json']);

        (new ContentService())->savePageContent($page, $data);

        if (preg_match('/^jazz_(\d+)$/', $page, $m)) {
            (new EventRepository())->syncJazzArtistMediaFromAdmin((int) $m[1], $data, $tracksJson, $imagesJson);
        }

        header('Location: /admin?saved=1#content');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    /**
     * Image upload endpoint for the WYSIWYG editor.
     * Returns JSON: { "location": "/uploads/filename.ext" }
     */
    public function uploadImage($vars = []): void
    {
        try {
        // Catch PHP notices/deprecations so the body stays valid JSON for fetch().
        ob_start();
        $status = 200;
        $payload = ['error' => 'Unexpected error'];

        try {
            if (!Csrf::validateRequest()) {
                $status = 403;
                $payload = ['error' => 'Invalid CSRF token'];
            } elseif (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
                $status = 403;
                $payload = ['error' => 'Unauthorized'];
            } elseif (!isset($_FILES['file']) || ($_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $status = 400;
                $payload = ['error' => 'No file uploaded'];
            } else {
                $imageService = new ImageUploadService(
                    __DIR__ . '/../../public/uploads/',
                    '/uploads/'
                );
                $path = $imageService->upload($_FILES['file'], 'img');
                $payload = ['location' => $path];
            }
        } catch (\Throwable $e) {
            $status = 400;
            $payload = ['error' => $e->getMessage()];
            error_log('admin uploadImage: ' . $e->getMessage());
        }

        $stray = ob_get_clean();
        if ($stray !== '') {
            error_log('admin uploadImage stray output (PHP warnings/notices): ' . $stray);
        }

        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            if (!headers_sent()) {
                http_response_code(500);
                header('Content-Type: application/json; charset=utf-8');
            }
            echo json_encode(['error' => 'Something went wrong.']);
        }
    }

    
     // Audio upload for jazz track previews. Returns JSON: { "location": "/uploads/audio/..." }
     
    public function uploadAudio($vars = []): void
    {
        try {
        ob_start();
        $status  = 200;
        $payload = ['error' => 'Unexpected error'];

        try {
            if (!Csrf::validateRequest()) {
                $status = 403;
                $payload = ['error' => 'Invalid CSRF token'];
            } elseif (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
                $status = 403;
                $payload = ['error' => 'Unauthorized'];
            } elseif (!isset($_FILES['file']) || ($_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $status = 400;
                $payload = ['error' => 'No file uploaded'];
            } else {
                $audioService = new AudioUploadService(
                    __DIR__ . '/../../public/uploads/audio/',
                    '/uploads/audio/'
                );
                $path = $audioService->upload($_FILES['file'], 'track');
                $payload = ['location' => $path];
            }
        } catch (\Throwable $e) {
            $status = 400;
            $payload = ['error' => $e->getMessage()];
            error_log('admin uploadAudio: ' . $e->getMessage());
        }

        $stray = ob_get_clean();
        if ($stray !== '') {
            error_log('admin uploadAudio stray output: ' . $stray);
        }

        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            if (!headers_sent()) {
                http_response_code(500);
                header('Content-Type: application/json; charset=utf-8');
            }
            echo json_encode(['error' => 'Something went wrong.']);
        }
    }

   
     // POST /admin/jazz/artists/create — add a jazz artist (events + jazz_events rows).
     
    public function createJazzArtist($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            $to = ($_POST['admin_return'] ?? '') === 'events' ? 'events' : 'content';
            $tab = $to === 'events' ? '&events_tab=jazz' : '';
            $hash = $to === 'events' ? '#events' : '#content';
            header('Location: /admin?jazz_error=' . rawurlencode('Invalid session. Please try again.') . $tab . $hash);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin#content');
            exit;
        }

        try {
            $repo = new EventRepository();
            $repo->createJazzArtist($_POST);
        } catch (\Throwable $e) {
            error_log('createJazzArtist: ' . $e->getMessage());
            $to = ($_POST['admin_return'] ?? '') === 'events' ? 'events' : 'content';
            $hash = $to === 'events' ? '#events' : '#content';
            $tab = $to === 'events' ? '&events_tab=jazz' : '';
            header('Location: /admin?jazz_error=' . rawurlencode($e->getMessage()) . $tab . $hash);
            exit;
        }

        $to = ($_POST['admin_return'] ?? '') === 'events' ? 'events' : 'content';
        if ($to === 'events') {
            header('Location: /admin?jazz_notice=created&events_tab=jazz#events');
        } else {
            header('Location: /admin?jazz_notice=created#content');
        }
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    
     //POST /admin/jazz/artists/{id}/delete — remove artist and jazz CMS rows.
     
    public function deleteJazzArtist($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            $to = ($_POST['admin_return'] ?? '') === 'events' ? 'events' : 'content';
            $tab = $to === 'events' ? '&events_tab=jazz' : '';
            $hash = $to === 'events' ? '#events' : '#content';
            header('Location: /admin?jazz_error=' . rawurlencode('Invalid session. Please try again.') . $tab . $hash);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin#content');
            exit;
        }

        $id = (int) ($vars['id'] ?? 0);

        try {
            (new EventRepository())->deleteJazzArtist($id);
        } catch (\Throwable $e) {
            error_log('deleteJazzArtist: ' . $e->getMessage());
            $to = ($_POST['admin_return'] ?? '') === 'events' ? 'events' : 'content';
            $tab = $to === 'events' ? '&events_tab=jazz' : '';
            $hash = $to === 'events' ? '#events' : '#content';
            header('Location: /admin?jazz_error=' . rawurlencode($e->getMessage()) . $tab . $hash);
            exit;
        }

        $to = ($_POST['admin_return'] ?? '') === 'events' ? 'events' : 'content';
        if ($to === 'events') {
            header('Location: /admin?jazz_notice=deleted&events_tab=jazz#events');
        } else {
            header('Location: /admin?jazz_notice=deleted#content');
        }
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

 
    public function createUser($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?user_error=csrf#users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin#users');
            exit;
        }

        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');
        $role     = $_POST['role']          ?? 'customer';

        $validator = new Validator();
        $validator
            ->validateRequired($name, 'Name')
            ->validateRequired($email, 'Email')
            ->validateEmail($email)
            ->validateRequired($password, 'Password')
            ->validateRole($role, ['customer', 'employee', 'admin']);

        if ($validator->hasErrors()) {
            header('Location: /admin?user_error=invalid_data#users');
            exit;
        }

        if ($this->userRepo->emailExists($email)) {
            header('Location: /admin?user_error=email_exists#users');
            exit;
        }

        $this->userRepo->adminCreateUser($name, $email, $password, $role);
        header('Location: /admin?user_saved=1#users');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function updateUser($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?user_error=csrf#users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin#users');
            exit;
        }

        $id    = (int) ($vars['id'] ?? 0);
        $name  = trim($_POST['name']  ?? '');
        $email = trim($_POST['email'] ?? '');
        $role  = $_POST['role']       ?? '';

        $validator = new Validator();
        $validator
            ->validateRequired($name, 'Name')
            ->validateRequired($email, 'Email')
            ->validateEmail($email)
            ->validateRole($role, ['customer', 'employee', 'admin']);

        if ($id <= 0 || $validator->hasErrors()) {
            header('Location: /admin?user_error=invalid_data#users');
            exit;
        }

        if ($this->userRepo->emailExistsForOtherUser($email, $id)) {
            header('Location: /admin?user_error=email_exists#users');
            exit;
        }

        $this->userRepo->adminUpdateUser($id, $name, $email, $role);
        header('Location: /admin?user_saved=1#users');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function deleteUser($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?user_error=csrf#users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin#users');
            exit;
        }

        $id = (int) ($vars['id'] ?? 0);

        if ($id <= 0 || $id === (int) ($_SESSION['user_id'] ?? 0)) {
            header('Location: /admin?user_error=invalid_id#users');
            exit;
        }

        $this->userRepo->deleteById($id);
        header('Location: /admin?user_saved=1#users');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    
     // Create a new storytelling event (admin CMS).
     
    public function createStoryEvent($vars = []): void
    {
        try {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }

        if (!Csrf::validateRequest()) {
            header('Location: /admin?story_error=csrf&events_tab=story#events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin?events_tab=story#events');
            exit;
        }

        $title      = trim($_POST['title']      ?? '');
        $day        = trim($_POST['day']        ?? '');
        $timeSlot   = trim($_POST['time_slot']  ?? '');
        $location   = trim($_POST['location']   ?? '');
        $eventDate  = trim($_POST['event_date'] ?? '');
        $ageGroup   = trim($_POST['age_group']  ?? '');
        $language   = trim($_POST['language']   ?? '');
        $price      = trim($_POST['price']      ?? '');
        $category   = trim($_POST['category']   ?? '');

        if ($title === '' || $day === '' || $timeSlot === '' || $location === '') {
            header('Location: /admin?story_error=invalid_data&events_tab=story#events');
            exit;
        }

        $repo = new StoryEventRepository();
        $repo->create([
            'title'      => $title,
            'day'        => $day,
            'time_slot'  => $timeSlot,
            'location'   => $location,
            'event_date' => $eventDate !== '' ? $eventDate : null,
            'age_group'  => $ageGroup !== '' ? $ageGroup : null,
            'language'   => $language !== '' ? $language : null,
            'price'      => $price !== '' ? $price : null,
            'category'   => $category !== '' ? $category : null,
        ]);

        header('Location: /admin?story_saved=1&events_tab=story#events');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    /**
     * Update an existing storytelling event.
     */
    public function updateStoryEvent($vars = []): void
    {
        try {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }

        if (!Csrf::validateRequest()) {
            header('Location: /admin?story_error=csrf&events_tab=story#events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin?events_tab=story#events');
            exit;
        }

        $id = (int) ($vars['id'] ?? 0);
        $title      = trim($_POST['title']      ?? '');
        $day        = trim($_POST['day']        ?? '');
        $timeSlot   = trim($_POST['time_slot']  ?? '');
        $location   = trim($_POST['location']   ?? '');
        $eventDate  = trim($_POST['event_date'] ?? '');
        $ageGroup   = trim($_POST['age_group']  ?? '');
        $language   = trim($_POST['language']   ?? '');
        $price      = trim($_POST['price']      ?? '');
        $category   = trim($_POST['category']   ?? '');

        if ($id <= 0 || $title === '' || $day === '' || $timeSlot === '' || $location === '') {
            header('Location: /admin?story_error=invalid_data&events_tab=story#events');
            exit;
        }

        $repo = new StoryEventRepository();
        $repo->update($id, [
            'title'      => $title,
            'day'        => $day,
            'time_slot'  => $timeSlot,
            'location'   => $location,
            'event_date' => $eventDate !== '' ? $eventDate : null,
            'age_group'  => $ageGroup !== '' ? $ageGroup : null,
            'language'   => $language !== '' ? $language : null,
            'price'      => $price !== '' ? $price : null,
            'category'   => $category !== '' ? $category : null,
        ]);

        header('Location: /admin?story_saved=1&events_tab=story#events');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    /**
     * Delete a storytelling event.
     */
    public function deleteStoryEvent($vars = []): void
    {
        try {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }

        if (!Csrf::validateRequest()) {
            header('Location: /admin?story_error=csrf&events_tab=story#events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin?events_tab=story#events');
            exit;
        }

        $id = (int) ($vars['id'] ?? 0);
        if ($id <= 0) {
            header('Location: /admin?story_error=invalid_id&events_tab=story#events');
            exit;
        }

        $repo = new StoryEventRepository();
        $repo->delete($id);

        header('Location: /admin?story_saved=1&events_tab=story#events');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }


    public function updateJazzArtist($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?jazz_error=' . rawurlencode('Invalid session.') . '&events_tab=jazz#events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin?events_tab=jazz#events');
            exit;
        }

        $id = (int) ($vars['id'] ?? 0);

        try {
            (new EventRepository())->updateJazzArtist($id, $_POST);
        } catch (\Throwable $e) {
            error_log('updateJazzArtist: ' . $e->getMessage());
            header('Location: /admin?jazz_error=' . rawurlencode($e->getMessage()) . '&events_tab=jazz#events');
            exit;
        }

        header('Location: /admin?jazz_notice=updated&events_tab=jazz#events');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function createHistoryTour($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?history_error=csrf&events_tab=history#events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin?events_tab=history#events');
            exit;
        }

        $guide = trim($_POST['guide_name'] ?? '');
        $lang = trim($_POST['language'] ?? '');

        try {
            (new HistoryTourRepository())->create($guide, $lang);
        } catch (\Throwable $e) {
            header('Location: /admin?history_error=' . rawurlencode($e->getMessage()) . '&events_tab=history#events');
            exit;
        }

        header('Location: /admin?history_saved=1&events_tab=history#events');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function updateHistoryTour($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?history_error=csrf&events_tab=history#events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin?events_tab=history#events');
            exit;
        }

        $id = (int) ($vars['id'] ?? 0);
        $guide = trim($_POST['guide_name'] ?? '');
        $lang = trim($_POST['language'] ?? '');

        try {
            (new HistoryTourRepository())->update($id, $guide, $lang);
        } catch (\Throwable $e) {
            header('Location: /admin?history_error=' . rawurlencode($e->getMessage()) . '&events_tab=history#events');
            exit;
        }

        header('Location: /admin?history_saved=1&events_tab=history#events');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function deleteHistoryTour($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?history_error=csrf&events_tab=history#events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin?events_tab=history#events');
            exit;
        }

        $id = (int) ($vars['id'] ?? 0);

        try {
            (new HistoryTourRepository())->delete($id);
        } catch (\Throwable $e) {
            header('Location: /admin?history_error=' . rawurlencode($e->getMessage()) . '&events_tab=history#events');
            exit;
        }

        header('Location: /admin?history_saved=1&events_tab=history#events');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function createYummyRestaurant($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?yummy_error=csrf&events_tab=yummy#events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin?events_tab=yummy#events');
            exit;
        }

        try {
            (new YummyRestaurantAdminRepository())->create($_POST);
        } catch (\Throwable $e) {
            header('Location: /admin?yummy_error=' . rawurlencode($e->getMessage()) . '&events_tab=yummy#events');
            exit;
        }

        header('Location: /admin?yummy_saved=1&events_tab=yummy#events');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function updateYummyRestaurant($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?yummy_error=csrf&events_tab=yummy#events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin?events_tab=yummy#events');
            exit;
        }

        $id = (int) ($vars['id'] ?? 0);

        try {
            (new YummyRestaurantAdminRepository())->update($id, $_POST);
        } catch (\Throwable $e) {
            header('Location: /admin?yummy_error=' . rawurlencode($e->getMessage()) . '&events_tab=yummy#events');
            exit;
        }

        header('Location: /admin?yummy_saved=1&events_tab=yummy#events');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function deactivateYummyRestaurant($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?yummy_error=csrf&events_tab=yummy#events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin?events_tab=yummy#events');
            exit;
        }

        $id = (int) ($vars['id'] ?? 0);

        try {
            (new YummyRestaurantAdminRepository())->deactivate($id);
        } catch (\Throwable $e) {
            header('Location: /admin?yummy_error=' . rawurlencode($e->getMessage()) . '&events_tab=yummy#events');
            exit;
        }

        header('Location: /admin?yummy_saved=1&events_tab=yummy#events');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function reactivateYummyRestaurant($vars = []): void
    {
        try {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin?yummy_error=csrf&events_tab=yummy#events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin?events_tab=yummy#events');
            exit;
        }

        $id = (int) ($vars['id'] ?? 0);

        try {
            (new YummyRestaurantAdminRepository())->reactivate($id);
        } catch (\Throwable $e) {
            header('Location: /admin?yummy_error=' . rawurlencode($e->getMessage()) . '&events_tab=yummy#events');
            exit;
        }

        header('Location: /admin?yummy_saved=1&events_tab=yummy#events');
        exit;
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    private function requireAdmin(): void
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
    }
}

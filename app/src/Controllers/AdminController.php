<?php

namespace App\Controllers;

use App\Services\ContentService;
use App\Services\ImageUploadService;
use App\Services\Validator;
use App\Repositories\UserRepository;

class AdminController
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    public function dashboard($vars = [])
    {
        $this->requireAdmin();

        $contentService = new ContentService();
        $homepageContent = $contentService->getPageContent('homepage');
        $storiesContent  = $contentService->getPageContent('stories');
        $yummyContent    = $contentService->getPageContent('yummy');
        $historyContent  = $contentService->getPageContent('history');
        $jazzContent     = $contentService->getPageContent('jazz');

        $userSearch = $_GET['search'] ?? '';
        $userRole   = $_GET['role']   ?? '';
        $userSort   = $_GET['sort']   ?? 'id';
        $userDir    = $_GET['dir']    ?? 'ASC';
        $users      = $this->userRepo->getAllUsers($userSearch, $userRole, $userSort, $userDir);
        $totalUsers = $this->userRepo->countAll();
        $userError  = $_GET['user_error'] ?? '';
        $userSaved  = $_GET['user_saved'] ?? '';

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    // -------------------------------------------------------------------------
    // Content
    // -------------------------------------------------------------------------

    public function saveContent($vars = []): void
    {
        $this->requireAdmin();

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
        unset($data['page']);

        (new ContentService())->savePageContent($page, $data);

        header('Location: /admin?saved=1#content');
        exit;
    }

    /**
     * Image upload endpoint for the WYSIWYG editor.
     * Returns JSON: { "location": "/uploads/filename.ext" }
     */
    public function uploadImage($vars = []): void
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        if (!isset($_FILES['file']) || ($_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['error' => 'No file uploaded']);
            return;
        }

        try {
            $imageService = new ImageUploadService(
                __DIR__ . '/../../public/uploads/',
                '/uploads/'
            );
            $path = $imageService->upload($_FILES['file'], 'img');
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode(['location' => $path]);
    }

    // -------------------------------------------------------------------------
    // User management
    // -------------------------------------------------------------------------

    public function createUser($vars = []): void
    {
        $this->requireAdmin();

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
    }

    public function updateUser($vars = []): void
    {
        $this->requireAdmin();

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
    }

    public function deleteUser($vars = []): void
    {
        $this->requireAdmin();

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
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function requireAdmin(): void
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
    }
}

<?php

namespace App\Controllers;

use App\Services\ContentService;

class AdminController
{
    public function dashboard($vars = [])
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }

        $contentService = new ContentService();
        $homepageContent = $contentService->getPageContent('homepage');
        $storiesContent = $contentService->getPageContent('stories');
        $yummyContent = $contentService->getPageContent('yummy');
        $historyContent = $contentService->getPageContent('history');
        $jazzContent = $contentService->getPageContent('jazz');

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    /**
     * Handle POSTed content updates from the admin CMS.
     */
    public function saveContent($vars = []): void
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /login');
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
        unset($data['page']);

        $service = new ContentService();
        $service->savePageContent($page, $data);

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

        $uploadDir = __DIR__ . '/../../public/uploads';
        $uploadUrlBase = '/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        if (!isset($_FILES['file']) || ($_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['error' => 'No file uploaded']);
            return;
        }

        $tmpName = $_FILES['file']['tmp_name'];
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmpName);

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        if (!isset($allowed[$mime])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid file type']);
            return;
        }

        $extension = $allowed[$mime];
        $filename = uniqid('img_', true) . '.' . $extension;
        $destination = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($tmpName, $destination)) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to move uploaded file']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode(['location' => $uploadUrlBase . $filename]);
    }
}

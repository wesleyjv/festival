<?php

namespace App\Controllers;

use App\Services\ContentService;

class HomeController
{
    use HandlesControllerErrors;

    public function home($vars = [])
    {
        try {
            $contentService = new ContentService();
            $homepageContent = $contentService->getPageContent('homepage');

            require __DIR__ . '/../views/main/homepage.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }
}

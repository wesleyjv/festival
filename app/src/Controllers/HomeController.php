<?php

namespace App\Controllers;

use App\Services\ContentService;

class HomeController
{
    public function home($vars = [])
    {
        $contentService = new ContentService();
        $homepageContent = $contentService->getPageContent('homepage');

        require __DIR__ . '/../views/main/homepage.php';
    }
}

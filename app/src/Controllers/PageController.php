<?php

namespace App\Controllers;


 // Simple static pages (About, Contact). Uses same layout as homepage; sets $currentRoute for nav.
 
class PageController
{
    use HandlesControllerErrors;

    public function about(array $vars = []): void
    {
        try {
        $currentRoute = 'about';
        $pageTitle = 'About – The Festival';
        $mainView = __DIR__ . '/../views/pages/about.php';
        $data = (object) [
            'footerLinks' => (new \App\Services\FestivalConfigService())->getFooterLinks(),
            'footerSocialUrls' => (new \App\Services\FestivalConfigService())->getFooterSocialUrls(),
        ];
        require __DIR__ . '/../views/layouts/main.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }

    public function contact(array $vars = []): void
    {
        try {
        $currentRoute = 'contact';
        $pageTitle = 'Contact – The Festival';
        $mainView = __DIR__ . '/../views/pages/contact.php';
        $data = (object) [
            'footerLinks' => (new \App\Services\FestivalConfigService())->getFooterLinks(),
            'footerSocialUrls' => (new \App\Services\FestivalConfigService())->getFooterSocialUrls(),
        ];
        require __DIR__ . '/../views/layouts/main.php';
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }
}

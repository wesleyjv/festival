<?php

namespace App\Services\Interfaces;

/** Contract for reading editable page content (CMS). */
interface IContentService
{
    /**
     * Return the editable content for a page, merged over its defaults.
     *
     * @return array<string,string>
     */
    public function getPageContent(string $page): array;
}

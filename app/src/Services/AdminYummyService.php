<?php

namespace App\Services;

use App\Repositories\Interfaces\IAdminYummyRepository;
use App\Services\Interfaces\IAdminYummyService;

/** Handles admin business logic for Yummy restaurants and their menu items. */
class AdminYummyService implements IAdminYummyService
{
    public function __construct(
        private readonly IAdminYummyRepository $adminYummyRepository
    ) {
    }

    /** Includes inactive restaurants so the admin can see and reactivate them. */
    public function findAllRestaurantsForAdmin(): array
    {
        return $this->adminYummyRepository->findAllRestaurantsForAdmin();
    }

    /** Returns a single restaurant row, or null if the ID does not exist. */
    public function findRestaurantById(int $restaurantId): ?array
    {
        return $this->adminYummyRepository->findRestaurantById($restaurantId);
    }

    /** Inserts a new restaurant; slug is generated automatically from the name. Returns the new restaurant's ID. */
    public function createRestaurant(array $formData): int
    {
        $this->validateRestaurantFormData($formData);

        $name = trim($formData['restaurant_name'] ?? '');
        $data = $formData;
        $data['name']     = $name;
        $data['slug']     = $this->generateSlug($name);
        $data['about']    = $this->sanitizeRichText($formData['about']    ?? null);
        $data['chef_bio'] = $this->sanitizeRichText($formData['chef_bio'] ?? null);

        return $this->adminYummyRepository->createRestaurant($data);
    }

    /** Updates all editable columns on the restaurants row. */
    public function updateRestaurant(int $restaurantId, array $formData): void
    {
        $this->validateRestaurantFormData($formData);

        $name = trim($formData['restaurant_name'] ?? '');
        $data = $formData;
        $data['name']     = $name;
        $data['slug']     = $this->generateSlug($name);
        $data['about']    = $this->sanitizeRichText($formData['about']    ?? null);
        $data['chef_bio'] = $this->sanitizeRichText($formData['chef_bio'] ?? null);

        $this->adminYummyRepository->updateRestaurant($restaurantId, $data);
    }

    /** Deletes menu items and cuisine tags first to satisfy FK constraints, then deletes the restaurant. */
    public function deleteRestaurant(int $restaurantId): void
    {
        $this->adminYummyRepository->deleteRestaurant($restaurantId);
    }

    /** Flips the `active` flag between 0 and 1. */
    public function toggleRestaurantActiveStatus(int $restaurantId): void
    {
        $this->adminYummyRepository->toggleRestaurantActiveStatus($restaurantId);
    }

    /** Returns all menu items for the given restaurant ordered by display_order. */
    public function findMenuItemsByRestaurantId(int $restaurantId): array
    {
        return $this->adminYummyRepository->findMenuItemsByRestaurantId($restaurantId);
    }

    /** Returns a single menu item row by its primary key, or null when not found. */
    public function findMenuItemById(int $itemId): ?array
    {
        return $this->adminYummyRepository->findMenuItemById($itemId);
    }

    /** Inserts a new menu item when formData has no item_id, otherwise updates the existing one. */
    public function saveMenuItem(int $restaurantId, array $formData): void
    {
        if (trim($formData['name'] ?? '') === '') {
            throw new \InvalidArgumentException('Menu item name is required.');
        }

        $data = $formData;
        $data['name']        = trim($formData['name'] ?? '');
        $data['description'] = $this->sanitizeRichText($formData['description'] ?? null);

        $this->adminYummyRepository->saveMenuItem($restaurantId, $data);
    }

    /** Permanently removes a single menu item row. */
    public function deleteMenuItem(int $menuItemId): void
    {
        $this->adminYummyRepository->deleteMenuItem($menuItemId);
    }

    /** Converts a restaurant name to a URL-safe slug. */
    private function generateSlug(string $name): string
    {
        return trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name)), '-');
    }

    private function validateRestaurantFormData(array $formData): void
    {
        if (trim($formData['restaurant_name'] ?? '') === '') {
            throw new \InvalidArgumentException('Restaurant name is required.');
        }

        foreach (['adult_price_cents', 'child_price_cents'] as $field) {
            $val = $formData[$field] ?? '';
            if ($val !== '' && (!is_numeric($val) || (float) $val < 0)) {
                throw new \InvalidArgumentException('Prices must be a number of 0 or more.');
            }
        }

        foreach (['seats', 'session_count', 'session_duration_minutes'] as $field) {
            $val = $formData[$field] ?? '';
            if ($val !== '' && filter_var($val, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
                throw new \InvalidArgumentException('Seats and session values must be 0 or more.');
            }
        }
    }

    /** Strips disallowed tags from TinyMCE output before storage; returns null for empty input. */
    private function sanitizeRichText(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return null;
        }

        $clean = strip_tags(
            $html,
            '<p><br><strong><b><em><i><u><ul><ol><li><h2><h3><blockquote><a>'
        );

        return $clean !== '' ? $clean : null;
    }

    /** Returns all cuisine tags for the CMS picker. */
    public function findAllCuisineTags(): array
    {
        return $this->adminYummyRepository->findAllCuisineTags();
    }

    /** Returns the cuisine tag IDs currently assigned to the given restaurant. */
    public function findCuisineTagIdsForRestaurant(int $restaurantId): array
    {
        return $this->adminYummyRepository->findCuisineTagIdsForRestaurant($restaurantId);
    }

    /** Replaces the restaurant's cuisine tag assignments with the given tag IDs. */
    public function setCuisineTagsForRestaurant(int $restaurantId, array $tagIds): void
    {
        $this->adminYummyRepository->setCuisineTagsForRestaurant($restaurantId, $tagIds);
    }
}

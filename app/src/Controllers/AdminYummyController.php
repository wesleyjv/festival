<?php

namespace App\Controllers;

use App\Security\Csrf;
use App\Services\Interfaces\IAdminYummyService;

/** Handles admin pages and POST actions for managing Yummy restaurants and their menu items. */
class AdminYummyController
{
    public function __construct(private readonly IAdminYummyService $adminYummyService)
    {
    }

    /** Redirects old /admin/yummy/restaurants bookmarks to the Events > Yummy tab. */
    public function redirectToEventsTab(array $vars = []): void
    {
        header('Location: /admin?events_tab=yummy#events');
        exit;
    }

    /** Fetches all restaurants (including inactive) and renders the list view. */
    public function displayRestaurantList(array $vars = []): void
    {
        $this->requireAdmin();

        try {
            $restaurants = $this->adminYummyService->findAllRestaurantsForAdmin();

            require __DIR__ . '/../views/admin/yummy/restaurant-list.php';
        } catch (\Throwable $e) {
            error_log('AdminYummyController::displayRestaurantList — ' . $e->getMessage());
            http_response_code(500);
            echo 'Unable to load the restaurant list.';
        }
    }

    /**
     * Serves the create form (no ?id param) or the edit form (?id=N).
     * Also fetches existing menu items when editing so the view can render the menu section.
     */
    public function displayRestaurantEditForm(array $vars = []): void
    {
        $this->requireAdmin();

        try {
            $restaurantId = isset($_GET['id']) ? (int) $_GET['id'] : null;
            $restaurant   = null;
            $menuItems    = [];

            $selectedCuisineTagIds = [];

            if ($restaurantId !== null) {
                $restaurant = $this->adminYummyService->findRestaurantById($restaurantId);

                if ($restaurant === null) {
                    http_response_code(404);
                    echo '404 – Restaurant not found.';
                    return;
                }

                $menuItems             = $this->adminYummyService->findMenuItemsByRestaurantId($restaurantId);
                $selectedCuisineTagIds = $this->adminYummyService->findCuisineTagIdsForRestaurant($restaurantId);
            }

            $cuisineTags = $this->adminYummyService->findAllCuisineTags();

            require __DIR__ . '/../views/admin/yummy/restaurant-edit.php';
        } catch (\Throwable $e) {
            error_log('AdminYummyController::displayRestaurantEditForm — ' . $e->getMessage());
            http_response_code(500);
            echo 'Unable to load the edit form.';
        }
    }

    /** Inserts a new restaurant using form data from $_POST. */
    public function createRestaurant(array $vars = []): void
    {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid session. Please try again.'));
            exit;
        }

        try {
            $restaurantId = $this->adminYummyService->createRestaurant($_POST);
            $this->adminYummyService->setCuisineTagsForRestaurant($restaurantId, $this->parseCuisineTagIds($_POST));
        } catch (\Throwable $e) {
            error_log('AdminYummyController::createRestaurant — ' . $e->getMessage());
            header('Location: /admin/yummy/restaurants/create?yummy_error=' . rawurlencode($e->getMessage()));
            exit;
        }

        header('Location: /admin/yummy/restaurants?yummy_notice=created');
        exit;
    }

    /** Saves updated fields for an existing restaurant. */
    public function updateRestaurant(array $vars = []): void
    {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid session. Please try again.'));
            exit;
        }

        $restaurantId = (int) ($_POST['restaurant_id'] ?? 0);

        if ($restaurantId <= 0) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid restaurant ID.'));
            exit;
        }

        try {
            $this->adminYummyService->updateRestaurant($restaurantId, $_POST);
            $this->adminYummyService->setCuisineTagsForRestaurant($restaurantId, $this->parseCuisineTagIds($_POST));
        } catch (\Throwable $e) {
            error_log('AdminYummyController::updateRestaurant — ' . $e->getMessage());
            header('Location: /admin/yummy/restaurants/edit?id=' . $restaurantId . '&yummy_error=' . rawurlencode($e->getMessage()));
            exit;
        }

        header('Location: /admin/yummy/restaurants?yummy_notice=updated');
        exit;
    }

    /** Permanently removes a restaurant and all its related rows — this cannot be undone. */
    public function deleteRestaurant(array $vars = []): void
    {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid session. Please try again.'));
            exit;
        }

        $restaurantId = (int) ($_POST['restaurant_id'] ?? 0);

        if ($restaurantId <= 0) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid restaurant ID.'));
            exit;
        }

        try {
            $this->adminYummyService->deleteRestaurant($restaurantId);
        } catch (\Throwable $e) {
            error_log('AdminYummyController::deleteRestaurant — ' . $e->getMessage());
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode($e->getMessage()));
            exit;
        }

        header('Location: /admin/yummy/restaurants?yummy_notice=deleted');
        exit;
    }

    /** Flips the restaurant's visibility on the public page without deleting any data. */
    public function toggleRestaurantActiveStatus(array $vars = []): void
    {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid session. Please try again.'));
            exit;
        }

        $restaurantId = (int) ($_POST['restaurant_id'] ?? 0);

        if ($restaurantId <= 0) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid restaurant ID.'));
            exit;
        }

        try {
            $this->adminYummyService->toggleRestaurantActiveStatus($restaurantId);
        } catch (\Throwable $e) {
            error_log('AdminYummyController::toggleRestaurantActiveStatus — ' . $e->getMessage());
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode($e->getMessage()));
            exit;
        }

        header('Location: /admin/yummy/restaurants');
        exit;
    }

    /**
     * Serves the menu item create form (?restaurant_id=N) or edit form (?restaurant_id=N&item_id=M).
     * Requires restaurant_id so the form can post back and the back link works.
     */
    public function displayMenuItemEditForm(array $vars = []): void
    {
        $this->requireAdmin();

        try {
            $restaurantId = (int) ($_GET['restaurant_id'] ?? 0);

            if ($restaurantId <= 0) {
                http_response_code(400);
                echo 'Missing restaurant_id.';
                return;
            }

            $itemId   = isset($_GET['item_id']) && (int) $_GET['item_id'] > 0
                        ? (int) $_GET['item_id']
                        : null;
            $menuItem = null;

            if ($itemId !== null) {
                $menuItem = $this->adminYummyService->findMenuItemById($itemId);
            }

            require __DIR__ . '/../views/admin/yummy/menu-item-edit.php';
        } catch (\Throwable $e) {
            error_log('AdminYummyController::displayMenuItemEditForm — ' . $e->getMessage());
            http_response_code(500);
            echo 'Unable to load the menu item form.';
        }
    }

    /** Inserts or updates a menu item depending on whether item_id is present in the POST body. */
    public function saveMenuItem(array $vars = []): void
    {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid session. Please try again.'));
            exit;
        }

        $restaurantId = (int) ($_POST['restaurant_id'] ?? 0);

        if ($restaurantId <= 0) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid restaurant ID.'));
            exit;
        }

        try {
            $this->adminYummyService->saveMenuItem($restaurantId, $_POST);
        } catch (\Throwable $e) {
            error_log('AdminYummyController::saveMenuItem — ' . $e->getMessage());
            header('Location: /admin/yummy/menu-items/edit?restaurant_id=' . $restaurantId . '&yummy_error=' . rawurlencode($e->getMessage()));
            exit;
        }

        header('Location: /admin/yummy/restaurants/edit?id=' . $restaurantId . '#menu-items');
        exit;
    }

    /** Permanently removes a menu item and redirects back to the restaurant edit page. */
    public function deleteMenuItem(array $vars = []): void
    {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid session. Please try again.'));
            exit;
        }

        $menuItemId   = (int) ($_POST['menu_item_id']  ?? 0);
        $restaurantId = (int) ($_POST['restaurant_id'] ?? 0);

        if ($menuItemId <= 0) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid menu item ID.'));
            exit;
        }

        try {
            $this->adminYummyService->deleteMenuItem($menuItemId);
        } catch (\Throwable $e) {
            error_log('AdminYummyController::deleteMenuItem — ' . $e->getMessage());
            header('Location: /admin/yummy/restaurants/edit?id=' . $restaurantId . '&yummy_error=' . rawurlencode($e->getMessage()) . '#menu-items');
            exit;
        }

        header('Location: /admin/yummy/restaurants/edit?id=' . $restaurantId . '#menu-items');
        exit;
    }

    /** Converts the posted cuisine_tags[] field into a list of positive integer tag IDs. */
    private function parseCuisineTagIds(array $postData): array
    {
        $raw = $postData['cuisine_tags'] ?? [];

        if (!is_array($raw)) {
            return [];
        }

        return array_values(array_filter(array_map('intval', $raw), static fn (int $id): bool => $id > 0));
    }

    /** Redirects unauthenticated or non-admin users to the login page. */
    private function requireAdmin(): void
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
    }
}

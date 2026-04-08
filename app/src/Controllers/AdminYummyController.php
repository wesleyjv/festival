<?php

namespace App\Controllers;

use App\Security\Csrf;
use App\Services\Interfaces\IAdminYummyService;

/** Handles admin pages and POST actions for managing Yummy event restaurants. */
class AdminYummyController
{
    public function __construct(private readonly IAdminYummyService $adminYummyService)
    {
    }

    /** Fetches all restaurants for the configured event and passes them to the list view. */
    public function displayRestaurantList(array $vars = []): void
    {
        $this->requireAdmin();

        try {
            $config      = require __DIR__ . '/../Config/yummy.php';
            $eventId     = $config['event_id'];
            $restaurants = $this->adminYummyService->getAllRestaurantsForEvent($eventId);

            require __DIR__ . '/../views/admin/yummy/restaurant-list.php';
        } catch (\Throwable $e) {
            error_log('AdminYummyController::displayRestaurantList — ' . $e->getMessage());
            http_response_code(500);
            echo 'Unable to load the restaurant list.';
        }
    }

    /**
     * Serves both the create form (no query param) and the edit form (GET ?id=N).
     * Returns 404 when an edit is requested for an ID that does not exist.
     */
    public function displayRestaurantEditForm(array $vars = []): void
    {
        $this->requireAdmin();

        try {
            $restaurantId = isset($_GET['id']) ? (int) $_GET['id'] : null;
            $restaurant   = null;

            if ($restaurantId !== null) {
                $restaurant = $this->adminYummyService->getRestaurantById($restaurantId);

                if ($restaurant === null) {
                    http_response_code(404);
                    echo '404 – Restaurant not found.';
                    return;
                }
            }

            require __DIR__ . '/../views/admin/yummy/restaurant-edit.php';
        } catch (\Throwable $e) {
            error_log('AdminYummyController::displayRestaurantEditForm — ' . $e->getMessage());
            http_response_code(500);
            echo 'Unable to load the edit form.';
        }
    }

    /** Inserts a new restaurant and links it to the configured Yummy event. */
    public function createRestaurant(array $vars = []): void
    {
        $this->requireAdmin();

        if (!Csrf::validateRequest()) {
            header('Location: /admin/yummy/restaurants?yummy_error=' . rawurlencode('Invalid session. Please try again.'));
            exit;
        }

        $config  = require __DIR__ . '/../Config/yummy.php';
        $eventId = $config['event_id'];

        try {
            $this->adminYummyService->createRestaurant($eventId, $_POST);
        } catch (\Throwable $e) {
            error_log('AdminYummyController::createRestaurant — ' . $e->getMessage());
            header('Location: /admin/yummy/restaurants/create?yummy_error=' . rawurlencode($e->getMessage()));
            exit;
        }

        header('Location: /admin/yummy/restaurants?yummy_notice=created');
        exit;
    }

    /** Saves updated fields for an existing restaurant and its event-link row. */
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
        } catch (\Throwable $e) {
            error_log('AdminYummyController::updateRestaurant — ' . $e->getMessage());
            header('Location: /admin/yummy/restaurants/edit?id=' . $restaurantId . '&yummy_error=' . rawurlencode($e->getMessage()));
            exit;
        }

        header('Location: /admin/yummy/restaurants?yummy_notice=updated');
        exit;
    }

    /** Permanently removes a restaurant and its event link — this action cannot be undone. */
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

    /** Redirects unauthenticated or non-admin users to the login page. */
    private function requireAdmin(): void
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
    }
}

<?php

require __DIR__ . '/../vendor/autoload.php';


/**
 * Load environment variables from the .env file at the project root.
 * This makes getenv() work regardless of how the app is started.
 */
$envPath = __DIR__ . '/../../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            putenv(trim($line));
        }
    }
    // Debug: log which Stripe env var (if any) is visible to the PHP process
    $stripeNames = ['STRIPE_SECRET', 'STRIPE_SECRET_KEY', 'STRIPE_API_SECRET', 'STRIPE_KEY', 'STRIPE_PRIVATE'];
    $found = null;
    foreach ($stripeNames as $n) {
        $v = getenv($n);
        if ($v !== false && strlen($v) > 0) { $found = $n; break; }
    }
    if ($found === null) {
        error_log('ENV DEBUG: No Stripe secret env var found (checked: ' . implode(', ', $stripeNames) . ')');
    } else {
        error_log('ENV DEBUG: Stripe secret visible in env var: ' . $found);
    }
}

/**
 * Secure session configuration.
 * - cookie_httponly: prevents JavaScript access to session cookie (XSS protection)
 * - cookie_samesite: prevents CSRF by restricting cross-site cookie sending
 * - use_strict_mode: rejects uninitialized session IDs
 * - use_only_cookies: prevents session fixation via URL parameters
 * - cookie_secure: only send cookie over HTTPS (disabled for local dev)
 */
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $isSecure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');

session_start();

\App\Security\Csrf::getToken();

/**
 * Auto-login via "Remember me" cookie.
 * If the user has no active session but carries a valid remember token cookie,
 * look up the user, verify the token hash, and restore the session.
 */
if (empty($_SESSION['user_id']) && !empty($_COOKIE['remember_token']) && !empty($_COOKIE['remember_user'])) {
    $tokenHash = hash('sha256', $_COOKIE['remember_token']);
    $userRepo = new App\Repositories\UserRepository();
    $user = $userRepo->findById((int) $_COOKIE['remember_user']);

    if ($user && !empty($user->passwordHash) && hash_equals($tokenHash, $userRepo->getRememberToken($user->id) ?? '')) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_profile_image'] = $user->profileImage;
    }
}

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\\Controllers\\HomeController', 'home']);
    $r->addRoute('GET', '/hello/{name}', ['App\\Controllers\\HelloController', 'greet']);
    
    $r->addRoute('GET', '/events/history', ['App\\Controllers\\EventsController', 'history']);
    $r->addRoute('GET', '/events/jazz', ['App\\Controllers\\EventsController', 'jazz']);
    $r->addRoute('GET', '/events/jazz/{id:\d+}', ['App\\Controllers\\EventsController', 'jazzDetail']);
    $r->addRoute('GET', '/events/stories', ['App\\Controllers\\EventsController', 'stories']);
    $r->addRoute('GET', '/events/stories/{id:\d+}', ['App\\Controllers\\EventsController', 'storyDetail']);
    $r->addRoute('GET', '/events/yummy', ['App\\Controllers\\EventsController', 'displayYummyOverviewPage']);
    $r->addRoute('GET', '/events/yummy/restaurant/{slug:[a-z0-9\-]+}', ['App\\Controllers\\EventsController', 'displayRestaurantDetailPage']);
    $r->addRoute('GET',  '/events/yummy/reservation/overview', ['App\\Controllers\\EventsController', 'displayReservationOverviewPage']);
    $r->addRoute('POST', '/events/yummy/reservation/confirm',  ['App\\Controllers\\EventsController', 'confirmReservation']);
    $r->addRoute('GET',  '/events/yummy/reservation/success',  ['App\\Controllers\\EventsController', 'displayReservationSuccessPage']);

    $r->addRoute('GET', '/tickets', ['App\\Controllers\\TicketController', 'index']);
    $r->addRoute('GET', '/events/history/order', ['App\\Controllers\\TicketController', 'historyTickets']);

    $r->addRoute('GET', '/employee/scan', ['App\\Controllers\\TicketScanController', 'index']);
    $r->addRoute('POST', '/employee/scan', ['App\\Controllers\\TicketScanController', 'scan']);

    $r->addRoute('GET', '/orders', ['App\\Controllers\\OrderController', 'orders']);
    $r->addRoute('GET', '/orders/{id:\d+}/download', ['App\\Controllers\\OrderController', 'download']);
    $r->addRoute('POST', '/orders/{id:\d+}/email', ['App\\Controllers\\OrderController', 'emailTickets']);
    $r->addRoute('GET', '/checkout', ['App\\Controllers\\OrderController', 'checkout']);
    $r->addRoute('POST', '/checkout', ['App\\Controllers\\OrderController', 'placeOrder']);
    $r->addRoute('GET', '/checkout/confirmation', ['App\\Controllers\\OrderController', 'confirmation']);
    $r->addRoute('GET', '/checkout/complete', ['App\\Controllers\\OrderController', 'completeCheckout']);

    $r->addRoute('POST', '/cart/add', ['App\\Controllers\\CartController', 'add']);
    $r->addRoute('POST', '/cart/remove', ['App\\Controllers\\CartController', 'remove']);
    $r->addRoute('GET', '/cart', ['App\\Controllers\\CartController', 'index']);
    $r->addRoute('GET', '/register', ['App\\Controllers\\UserController', 'register']);
    $r->addRoute('POST', '/register', ['App\\Controllers\\UserController', 'handleRegister']);
    $r->addRoute('GET', '/login', ['App\\Controllers\\UserController', 'login']);
    $r->addRoute('POST', '/login', ['App\\Controllers\\UserController', 'handleLogin']);
    $r->addRoute('GET', '/logout', ['App\\Controllers\\UserController', 'logout']);
    $r->addRoute('GET', '/profile', ['App\\Controllers\\UserController', 'profile']);
    $r->addRoute('POST', '/profile/update', ['App\\Controllers\\UserController', 'handleUpdateProfile']);
    $r->addRoute('GET',  '/admin', ['App\\Controllers\\AdminController', 'dashboard']);
    $r->addRoute('POST', '/admin/content/save', ['App\\Controllers\\AdminController', 'saveContent']);
    $r->addRoute('POST', '/admin/jazz/artists/create', ['App\\Controllers\\AdminController', 'createJazzArtist']);
    $r->addRoute('POST', '/admin/jazz/artists/{id:\d+}/delete', ['App\\Controllers\\AdminController', 'deleteJazzArtist']);
    $r->addRoute('POST', '/admin/upload-image', ['App\\Controllers\\AdminController', 'uploadImage']);
    $r->addRoute('POST', '/admin/upload-audio', ['App\\Controllers\\AdminController', 'uploadAudio']);
    $r->addRoute('POST', '/admin/users/create', ['App\\Controllers\\AdminController', 'createUser']);
    $r->addRoute('POST', '/admin/users/{id:\d+}/update', ['App\\Controllers\\AdminController', 'updateUser']);
    $r->addRoute('POST', '/admin/users/{id:\d+}/delete', ['App\\Controllers\\AdminController', 'deleteUser']);
    $r->addRoute('POST', '/admin/story-events/create', ['App\\Controllers\\AdminController', 'createStoryEvent']);
    $r->addRoute('POST', '/admin/story-events/{id:\d+}/update', ['App\\Controllers\\AdminController', 'updateStoryEvent']);
    $r->addRoute('POST', '/admin/story-events/{id:\d+}/delete', ['App\\Controllers\\AdminController', 'deleteStoryEvent']);

    $r->addRoute('GET',  '/admin/yummy/restaurants',                              ['App\\Controllers\\AdminYummyController', 'redirectToEventsTab']);
    $r->addRoute('GET',  '/admin/yummy/restaurants/create',                       ['App\\Controllers\\AdminYummyController', 'displayRestaurantEditForm']);
    $r->addRoute('GET',  '/admin/yummy/restaurants/edit',                         ['App\\Controllers\\AdminYummyController', 'displayRestaurantEditForm']);
    $r->addRoute('POST', '/admin/yummy/restaurants/create',                       ['App\\Controllers\\AdminYummyController', 'createRestaurant']);
    $r->addRoute('POST', '/admin/yummy/restaurants/update',                       ['App\\Controllers\\AdminYummyController', 'updateRestaurant']);
    $r->addRoute('POST', '/admin/yummy/restaurants/{id:\d+}/delete',              ['App\\Controllers\\AdminYummyController', 'deleteRestaurant']);
    $r->addRoute('POST', '/admin/yummy/restaurants/{id:\d+}/toggle-active',       ['App\\Controllers\\AdminYummyController', 'toggleRestaurantActiveStatus']);
    $r->addRoute('GET',  '/admin/yummy/menu-items/edit',                          ['App\\Controllers\\AdminYummyController', 'displayMenuItemEditForm']);
    $r->addRoute('POST', '/admin/yummy/menu-items/save',                          ['App\\Controllers\\AdminYummyController', 'saveMenuItem']);
    $r->addRoute('POST', '/admin/yummy/menu-items/delete',                        ['App\\Controllers\\AdminYummyController', 'deleteMenuItem']);
    $r->addRoute('POST', '/admin/jazz/artists/{id:\d+}/update', ['App\\Controllers\\AdminController', 'updateJazzArtist']);
    $r->addRoute('POST', '/admin/history-tours/create', ['App\\Controllers\\AdminController', 'createHistoryTour']);
    $r->addRoute('POST', '/admin/history-tours/{id:\d+}/update', ['App\\Controllers\\AdminController', 'updateHistoryTour']);
    $r->addRoute('POST', '/admin/history-tours/{id:\d+}/delete', ['App\\Controllers\\AdminController', 'deleteHistoryTour']);
    $r->addRoute('POST', '/admin/yummy-restaurants/create', ['App\\Controllers\\AdminController', 'createYummyRestaurant']);
    $r->addRoute('POST', '/admin/yummy-restaurants/{id:\d+}/update', ['App\\Controllers\\AdminController', 'updateYummyRestaurant']);
    $r->addRoute('POST', '/admin/yummy-restaurants/{id:\d+}/deactivate', ['App\\Controllers\\AdminController', 'deactivateYummyRestaurant']);
    $r->addRoute('POST', '/admin/yummy-restaurants/{id:\d+}/reactivate', ['App\\Controllers\\AdminController', 'reactivateYummyRestaurant']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
// Match routes registered without a trailing slash (e.g. /admin vs /admin/)
$uri = $uri !== '/' ? rtrim($uri, '/') : '/';
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404 - Page Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 - Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        [$controllerClass, $method] = $routeInfo[1];
        $vars = $routeInfo[2];

        // Wire up dependency injection for controllers that require it
        if ($controllerClass === App\Controllers\OrderController::class) {
            $orderRepository = new App\Repositories\OrderRepository();
            $orderService = new App\Services\OrderService($orderRepository);
            $ticketPdfService = new App\Services\TicketPdfService();
            $mailService = new App\Services\MailService();
            $stripeService = new App\Services\StripeService();
            $controller = new $controllerClass($orderService, $ticketPdfService, $mailService, $stripeService);
        } elseif ($controllerClass === App\Controllers\AdminYummyController::class) {
            $controller = new $controllerClass(new App\Services\AdminYummyService());
        } else {
            $controller = new $controllerClass();
        }
        $controller->$method($vars);
        break;
}
<?php

/**
 * This is the central route handler of the application.
 * It uses FastRoute to map URLs to controller methods.
 * 
 * See the documentation for FastRoute for more information: https://github.com/nikic/FastRoute
 */

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

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

/**
 * Define the routes for the application.
 */
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\\Controllers\\HomeController', 'home']);
    $r->addRoute('GET', '/hello/{name}', ['App\\Controllers\\HelloController', 'greet']);
    $r->addRoute('GET', '/events/history', ['App\\Controllers\\EventsController', 'history']);
    $r->addRoute('GET', '/events/jazz', ['App\\Controllers\\EventsController', 'jazz']);
    $r->addRoute('GET', '/events/stories', ['App\\Controllers\\EventsController', 'stories']);
    $r->addRoute('GET', '/events/yummy', ['App\\Controllers\\EventsController', 'yummy']);
    $r->addRoute('GET', '/register', ['App\\Controllers\\UserController', 'register']);
    $r->addRoute('POST', '/register', ['App\\Controllers\\UserController', 'handleRegister']);
    $r->addRoute('GET', '/login', ['App\\Controllers\\UserController', 'login']);
    $r->addRoute('POST', '/login', ['App\\Controllers\\UserController', 'handleLogin']);
    $r->addRoute('GET', '/logout', ['App\\Controllers\\UserController', 'logout']);
});

/**
 * Get the request method and URI from the server variables and invoke the dispatcher.
 */
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

/**
 * Switch on the dispatcher result and call the appropriate controller method if found.
 */
switch ($routeInfo[0]) {
    // Handle not found routes
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;
    // Handle routes that were invoked with the wrong HTTP method
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
    // Handle found routes
    case FastRoute\Dispatcher::FOUND:
        /**
         * $routeInfo contains the data about the matched route.
         * 
         * $routeInfo[1] is the whatever we define as the third argument the `$r->addRoute` method.
         *  For instance for: `$r->addRoute('GET', '/hello/{name}', ['App\\Controllers\\HelloController', 'greet']);`
         *  $routeInfo[1] will be `['App\\Controllers\\HelloController', 'greet']`
         * 
         * Hint: we can use class strings like `App\\Controllers\\HelloController` to create new instances of that class.
         * Hint: in PHP we can use a string to call a class method dynamically, like this: `$instance->$methodName($args);`
         */

        // $routeInfo[1] contains an array like [ControllerClass, method]
        [$controllerClass, $method] = $routeInfo[1];

        // Create the controller instance and call the method with route params
        $controller = new $controllerClass();
        $controller->$method($routeInfo[2]);

        break;
}

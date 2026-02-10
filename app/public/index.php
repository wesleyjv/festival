<?php

/**
 * This is the central route handler of the application.
 * It uses FastRoute to map URLs to controller methods.
 * 
 * See the documentation for FastRoute for more information: https://github.com/nikic/FastRoute
 */

require __DIR__ . '/../vendor/autoload.php';

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

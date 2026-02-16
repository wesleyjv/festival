<?php

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\\Controllers\\HomeController', 'home']);
    $r->addRoute('GET', '/hello/{name}', ['App\\Controllers\\HelloController', 'greet']);
    
    $r->addRoute('GET', '/events/history', ['App\\Controllers\\EventsController', 'history']);
    $r->addRoute('GET', '/events/jazz', ['App\\Controllers\\EventsController', 'jazz']);
    $r->addRoute('GET', '/events/stories', ['App\\Controllers\\EventsController', 'stories']);
    $r->addRoute('GET', '/events/yummy', ['App\\Controllers\\EventsController', 'yummy']);

    $r->addRoute('GET', '/tickets', ['App\\Controllers\\TicketController', 'index']);
    
    $r->addRoute('POST', '/cart/add', ['App\\Controllers\\CartController', 'add']);
    $r->addRoute('GET', '/cart', ['App\\Controllers\\CartController', 'index']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
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
        
        $controller = new $controllerClass();
        $controller->$method($vars);
        break;
}
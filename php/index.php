<?php
session_start();

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

require __DIR__ . '/vendor/autoload.php';

//autoloader
function autoloader($class_name)
{
    $directories = ['', '/controllers', '/views', '/templates', '/src', '/config'];
    foreach ($directories as $dir) {
        $file = __DIR__ . $dir . '/' . $class_name . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
}
spl_autoload_register('autoloader');

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

// This middleware will append the response header Access-Control-Allow-Methods with all allowed methods
$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    $routeContext = RouteContext::fromRequest($request);
    $routingResults = $routeContext->getRoutingResults();
    $methods = $routingResults->getAllowedMethods();
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

    $response = $handler->handle($request);

    $response = $response->withHeader('Access-Control-Allow-Origin', implode(',', Config::$CORS_domains));
    $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

    // Optional: Allow Ajax CORS requests with Authorization header
    $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

    return $response;
});

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//routes
$app->get('/', "DebugController:status");

$app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('', 'SessionController:login');
    $group->options('', function ($request, $response, $args){
        return $response;
    });
});

$app->group('/signin', function (RouteCollectorProxy $group) {
    $group->post('', "SessionController:signin");
    $group->options('', function ($request, $response, $args){
        return $response;
    });
});

$app->group('/logout', function (RouteCollectorProxy $group) {
    $group->get('', "SessionController:logout");
    $group->options('', function ($request, $response, $args){
        return $response;
    });
});

$app->run();

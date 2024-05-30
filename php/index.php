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
    $directories = ['', '/controllers', '/views', '/templates', '/src', '/src/dbClass', '/config'];
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

$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    $routeContext = RouteContext::fromRequest($request);
    $routingResults = $routeContext->getRoutingResults();
    $methods = $routingResults->getAllowedMethods();
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

    $response = $handler->handle($request);

    $response = $response->withHeader('Access-Control-Allow-Origin', Config::$cors_domain);
    $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);
    $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

    return $response;
});

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//routes
//StatusController
$app->group('/status', function (RouteCollectorProxy $group) {
    $group->get('', "StatusController:status");
    $group->options('', function ($request, $response, $args) {
        return $response;
    });
});

//SessionController
$app->group('/session', function (RouteCollectorProxy $group) {
    $group->post('/login', 'SessionController:login');
    $group->post('/signin', "SessionController:signin");
    $group->post('/logout', "SessionController:logout");

    //preflight options
    $group->options('/login', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/signin', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/logout', function ($request, $response, $args) {
        return $response;
    });
});

//UserController
$app->group('/user', function (RouteCollectorProxy $group) {
    $group->get('', "UserController:userInfo");
    $group->get('/{id}', "UserController:userInfoByID");

    //preflight options
    $group->options('', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/{id}', function ($request, $response, $args) {
        return $response;
    });
});

//GareController
$app->group('/gare', function (RouteCollectorProxy $group) {
    $group->get('', "GaraController:gare");
    $group->get('/aperte', "GaraController:gareAperte");
    $group->get('/user', "GaraController:gareIscritto");
    $group->get('/gestite', "GaraController:gareDellUtente");
    $group->get('/utenteNonIscritto', "GaraController:utenteNonIscritto");
    $group->post('/crea', "GaraController:creaGara");
    $group->post('/iscriviUtente/{id}', "GaraController:iscriviUtente");
    $group->post('/disiscriviUtente/{id}', "GaraController:disiscriviUtente");
    $group->get('/{id}', "GaraController:gara");

    //preflight options
    $group->options('', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/aperte', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/user', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/gestite', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/utenteNonIscritto', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/crea', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/iscriviUtente/{id}', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/disiscriviUtente/{id}', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/{id}', function ($request, $response, $args) {
        return $response;
    });
});

$app->run();

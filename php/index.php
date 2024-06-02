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
$app->group('/status', function (RouteCollectorProxy $group) { //Completato
    $group->get('', "StatusController:status"); //OK
    $group->options('', function ($request, $response, $args) {
        return $response;
    });
});

//SessionController
$app->group('/session', function (RouteCollectorProxy $group) { //Completato
    $group->post('/login', 'SessionController:login'); //OK
    $group->post('/signin', "SessionController:signin"); //OK
    $group->post('/logout', "SessionController:logout"); //OK

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
$app->group('/user', function (RouteCollectorProxy $group) { //Completato
    $group->get('', "UserController:userInfo"); //OK
    $group->get('/{id}', "UserController:userInfoByID"); //OK

    //preflight options
    $group->options('', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/{id}', function ($request, $response, $args) {
        return $response;
    });
});

//GareController
$app->group('/gare', function (RouteCollectorProxy $group) { // Completato
    //tutte le gare
    $group->get('', "GaraController:gare"); //OK
    //tutte le gare aperte
    $group->get('/aperte', "GaraController:gareAperte"); //OK
    //tutte le gare dove l'utente è iscritto
    $group->get('/user', "GaraController:gareIscritto"); //OK
    //tutte le gare moderate dall'utente
    $group->get('/gestite', "GaraController:gareDellUtente"); //OK
    //gare in cui l'utente non è iscritto
    $group->get('/utenteNonIscritto', "GaraController:utenteNonIscritto"); //OK
    //iscrivi utente alla gara
    $group->post('/iscriviUtente/{id}', "GaraController:iscriviUtente"); //OK
    //crea gara
    $group->post('/crea', "GaraController:creaGara"); //OK
    //crea gara
    $group->delete('/{id}/elimina', "GaraController:eliminaGara"); //OK
    //modifica gara
    $group->put('/{id}/modifica', "GaraController:modificaGara"); //OK
    //disiscrivi utente
    $group->delete('/{id}/disiscriviConcorrente/{id_utente}', "GaraController:disiscriviConcorrente"); //OK
    //aggiungi un'organizzatore (tramite email)
    $group->post('/{id}/aggiungiOrganizzatore', "GaraController:aggiungiOrganizzatore"); //OK
    //rimuovi un'organizzatore
    $group->delete('/{id}/rimuoviOrganizzatore/{id_utente}', "GaraController:rimuoviOrganizzatore"); //OK
    //dati di solo una gara
    $group->get('/{id}', "GaraController:gara"); //OK

    //preflight options (mantenere lo stesso ordine)
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
    $group->options('/iscriviUtente', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/crea', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/{id}/elimina', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/{id}/modifica', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/{id}/disiscriviConcorrente/{id_utente}', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/{id}/aggiungiOrganizzatore/{id_utente}', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/{id}/rimuoviOrganizzatore/{id_utente}', function ($request, $response, $args) {
        return $response;
    });
    $group->options('/{id}', function ($request, $response, $args) {
        return $response;
    });
});

$app->run();

<?php
session_start();
header("Access-Control-Allow-Origin: *");

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

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

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//routes
$app->get('/', "DebugController:status");

$app->group('/session', function (RouteCollectorProxy $group) {
    $group->post('/login', 'SessionController:login');
    $group->post('/signin', "SessionController:signin");
    $group->get('/logout', "SessionController:logout");
});

$app->run();

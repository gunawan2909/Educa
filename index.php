<?php
require_once __DIR__ . '/vendor/autoload.php';

use Medoo\Medoo;
use Valitron\Validator;


$config = ['settings' => [
    'displayErrorDetails' => true,
    'addContentLengthHeader' => false,
]];
$app = new \Slim\App($config);

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/resources/views', [
        'cache' => false
    ]);
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    return $view;
};

//Data base Config
$container['db'] =  new Medoo([
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'educa',
    'username' => 'root',
    'password' => ''
]);


//Controller Setup 
$container['SchoolController'] = function ($container) {
    return new \App\Controllers\SchoolController($container);
};
$container['StudentController'] = function ($container) {
    return new \App\Controllers\StudentController($container);
};


require_once __DIR__ . '/app/Route.php';

$app->run();

<?php

use Cart\App;
use Slim\Views\Twig;
use Illuminate\Database\Capsule\Manager as Capsule;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new App;

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

$container = $app->getContainer();

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => env('DB_CONNECTION'),
    'host' => env('DB_HOST'),
    'database' => env('DB_DATABASE'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('6zmzcbnpj9ybqpkj');
Braintree_Configuration::publicKey('swvvp48mntp5sx94');
Braintree_Configuration::privateKey('1dab62b909255675ac5fdf3f6d191275');

require __DIR__ . '/../app/routes.php';

$app->add(new \Cart\Middleware\ValidationErrorsMiddleware($container->get(Twig::class)));
$app->add(new \Cart\Middleware\OldInputMiddleware($container->get(Twig::class)));

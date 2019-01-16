<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Jenssegers\Lean\App();

$container = $app->getContainer();

$container->inflector(App\Controllers\Controller::class)->invokeMethod('setContainer', [$container]);

$container->get('settings')->replace([
    'displayErrorDetails' => getenv('APP_DEBUG') === 'true',

    'app' => [
        'name' => getenv('APP_NAME'),
        'debug' => getenv('APP_DEBUG') === 'true',
    ],

    'views' => [
        'cache' => getenv('VIEW_CACHE_DISABLED') === 'true' ? false : __DIR__ . '/../storage/views'
    ],

    'db' => [
        'driver' => getenv('DB_DRIVER'),
        'host' => getenv('DB_HOST'),
        'database' => getenv('DB_DATABASE'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'charset' => getenv('DB_CHARSET'),
        'collation' => getenv('DB_COLLATION'),
        'prefix' => getenv('DB_PREFIX')
    ],

    'auth' => [
        'alg' => getenv('AUTH_PASSWORD_ALG'),
        'salt' => getenv('AUTH_PASSWORD_SALT'),
        'cost' => getenv('AUTH_PASSWORD_COST')
    ],

    'log' => [
        'path' => __DIR__ . '/..' . getenv('LOG_PATH'),
        'level' => getenv('LOG_LEVEL'),
        'dateFormat' => getenv('LOG_DATE_FORMAT'),
        'outputFormat' => getenv('LOG_OUTPUT_FORMAT')
    ]
]);

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container->get('settings')->get('db'));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container->addServiceProvider(new App\Providers\ViewServiceProvider());
$container->addServiceProvider(new App\Providers\MonologServiceProvider());

require_once __DIR__ . '/../routes/web.php';

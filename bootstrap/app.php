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
        'name' => getenv('APP_NAME')
    ],

    'views' => [
        'cache' => getenv('VIEW_CACHE_DISABLED') === 'true' ? false : __DIR__ . '/../storage/views'
    ]
]);

$container->share('view', function () use ($container){
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => $container->get('settings')->get('views.cache')
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));

    return $view;
});

require_once __DIR__ . '/../routes/web.php';

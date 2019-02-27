<?php

use App\Auth\Auth;
use App\Auth\Controllers\LoginController;
use App\Auth\Controllers\LogoutController;
use App\Auth\Controllers\SignupController;
use App\Auth\Middleware\AuthMiddleware;
use App\Controllers\HomeController;
use App\Middleware\CsrfViewMiddleware;
use Slim\App;
use Slim\Csrf\Guard as CsrfMiddleware;
use Slim\Views\Twig;
use Psr\Http\Message\{
	ServerRequestInterface as Request,
	ResponseInterface as Response
};

$app->add($container->get(CsrfViewMiddleware::class));
$app->add($container->get(CsrfMiddleware::class));

$app->group('', function (App $app) {
	$app->get("/", HomeController::class . ":index")->setName('home');
	$app->get("/login", LoginController::class . ":index")->setName('login');
	$app->post("/login", LoginController::class . ":login");
	$app->get("/signup", SignupController::class . ":index")->setName('signup');
	$app->post("/signup", SignupController::class . ":signup");
})->add($container->get(AuthMiddleware::class)->withRedirectPathName('login')->withRoles([Auth::GUEST]));
$app->group('', function (App $app) {
	$app->get("/page1", function(Request $request, Response $response){
		return $this->get(Twig::class)->render($response, "page1.twig", [
			"text" => "Csoki",
		]);
	});
	$app->get("/logout", LogoutController::class . ":index");
})->add($container->get(AuthMiddleware::class)->withRedirectPathName('login')->withRoles([Auth::USER,Auth::ADMIN]));

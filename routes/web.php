<?php

use App\Auth\Controllers\LoginController;
use App\Auth\Controllers\LogoutController;
use App\Auth\Controllers\SignupController;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;
use Slim\App;
use Psr\Http\Message\{
	ServerRequestInterface as Request,
	ResponseInterface as Response
};
$app->get("/", HomeController::class . ":index")->setName('home');
$app->get("/login", LoginController::class . ":index")->setName('login');
$app->post("/login", LoginController::class . ":login");
$app->get("/signup", SignupController::class . ":index")->setName('signup');
$app->post("/signup", SignupController::class . ":signup");
$app->group('', function (App $app) {
	$app->get("/page1", function(Request $request, Response $response){
		return $this->get('view')->render($response, "page1.twig", [
			"text" => "Csoki",
		]);
	});
	$app->get("/logout", LogoutController::class . ":index");
})->add($container->get(AuthMiddleware::class)->withRedirectPathName('login'));

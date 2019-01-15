<?php

use App\Controllers\HomeController;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};
$app->get("/", HomeController::class . ":index");
$app->get("/page1", function(Request $request, Response $response){
	return $this->get('view')->render($response, "page1.twig", [
            "text" => "Csoki",
        ]);
});

<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class HomeController extends Controller
{
	public function index(Request $request, Response $response){
    	return $this->c->get('view')->render($response, 'home.twig', [
            'appName' => $this->c->get('settings')->get('app.name'),
        ]);
    }
}

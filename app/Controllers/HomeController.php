<?php declare(strict_types=1);

namespace App\Controllers;

use App\Auth\Auth;
use App\Auth\AuthRepositoryInterface as AuthRepository;
use App\Controllers\Controller;
use App\Models\User;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class HomeController extends Controller
{
	public function index(Request $request, Response $response, AuthRepository $authRepo){
    	return $this->container->get('view')->render($response, 'home.twig', [
            'appName' => $this->container->get('settings')->get('app.name'),
        ]);
    }
}

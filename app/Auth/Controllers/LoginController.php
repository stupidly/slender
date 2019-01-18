<?php declare(strict_types=1);

namespace App\Auth\Controllers;

use App\Auth\Auth;
use App\Controllers\Controller as Controller;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class LoginController extends Controller{

	protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function index(Request $request, Response $response)
    {
        if (!$token = $this->auth->attemptCredentials($request->getParam('username'), $request->getParam('password'))) {
            return $response->withStatus(401);
        }

        return $response->withJson([
            'token' => $token
        ]);
    }
}
<?php declare(strict_types=1);

namespace App\Auth\Controllers;

use App\Auth\Auth;
use App\Controllers\Controller as Controller;
use App\Validation\Validator;
use Respect\Validation\Validator as v;
use Slim\Router;
use Slim\Views\Twig;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class SignupController extends Controller{

	protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function index(Request $request, Response $response, Router $router, Auth $auth, Twig $view){
        $params = [];
        if($redirectUrl = $request->getParam('redirect-url')){
            $params['redirectUrl'] = $redirectUrl;
        }
        return $view->render($response, 'signup.twig', $params);
    }

    public function signup(Request $request, Response $response, Router $router, Auth $auth, Validator $validator)
    {
        $validation = $validator->validate($request, [
            "username" => v::noWhitespace()->notEmpty(),
            "password" => v::noWhitespace()->notEmpty(),
            "password_again" => v::noWhitespace()->notEmpty()->matchesTo($request->getParam("password")),
        ]);

        if ($validation->failed() || !$user = $this->auth->signUp($request->getParam('username'), $request->getParam('password'), $auth::USER)) {
            return $response->withRedirect($router->pathFor('signup'));
        }
        return $this->redirect($request, $response, $router);
    }

    protected function redirect(Request $request, Response $response, Router $router){
        $redirectUrl = $request->getParam('redirect-url');
        if($redirectUrl === "" || $redirectUrl === null){
            $redirectUrl = $router->pathFor('home');
        }
        return $response->withRedirect($redirectUrl);
    }
}
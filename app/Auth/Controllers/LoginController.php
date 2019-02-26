<?php declare(strict_types=1);

namespace App\Auth\Controllers;

use App\Auth\Auth;
use App\Controllers\Controller as Controller;
use Slim\Flash\Messages;
use Slim\Router;
use Symfony\Component\Translation\TranslatorInterface as Translator;
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

    public function index(Request $request, Response $response, Router $router, Auth $auth){
        try{
            $auth->authenticate($request);
            return $this->redirect($request, $response, $router);
        }catch(\Exception $e){}
        $params = [];
        if($redirectUrl = $request->getParam('redirect-url')){
            $params['redirectUrl'] = $redirectUrl;
        }
        return $this->container->get('view')->render($response, 'login.twig', $params);
    }

    public function login(Request $request, Response $response, Router $router, Auth $auth, Messages $messages, Translator $trans)
    {
        try{
            $user = $this->auth->attemptCredentials($request->getParam('username'), $request->getParam('password'));
            if($user === null) throw new \Exception('No such user');
            $messages->addMessage('success', $trans->trans('message.welcome', ['%name%'=>$user->username]));
            return $this->redirect($request, $response, $router);
        }catch(\Exception $e){
            return $response->withRedirect($router->pathFor('login'));
        }
    }

    protected function redirect(Request $request, Response $response, Router $router){
        $redirectUrl = $request->getParam('redirect-url');
        if($redirectUrl === "" || $redirectUrl === null){
            $redirectUrl = $router->pathFor('home');
        }

        return $response->withRedirect($redirectUrl);
    }
}
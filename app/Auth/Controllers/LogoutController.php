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

class LogoutController extends Controller{

	protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function index(Request $request, Response $response, Router $router, Messages $messages, Translator $trans)
    {
        $this->auth->logout($request);
        $messages->addMessage('success', $trans->trans('message.bye'));
        return $response->withRedirect($router->pathFor('login'));
    }
}
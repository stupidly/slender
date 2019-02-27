<?php

namespace App\Handlers;

use App\Auth\Auth;
use Psr\Log\LoggerInterface;
use Slim\Flash\Messages;
use Slim\Router;
use Symfony\Component\Translation\TranslatorInterface;

class ProductionErrorHandler {
    
    protected $log;
    protected $router;
    protected $auth;
    protected $trans;
    protected $messages;
    
    public function __construct(LoggerInterface $log, Router $router, TranslatorInterface $trans, Messages $messages, Auth $auth) {
        $this->log = $log;
        $this->router = $router;
        $this->trans = $trans;
        $this->messages = $messages;
        $this->auth = $auth;
    }

    public function __invoke($request, $response, \Error $error) {
        $this->messages->addMessage("error", $this->trans->trans("message.error_generic"));
        $this->log->warning("Visible error", [
            "uri" => $request->getUri()->getPath(),
            "user" => $this->auth->getUser()->username,
            "host" => $this->auth->getHost(),
            "message" => $error->getMessage(),
            "file" => $error->getFile(),
            "line" => $error->getLine(),
        ]);
        return $response->withRedirect($this->router->pathFor("login"));
    }

}

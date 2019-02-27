<?php

namespace App\Middleware;

use Slim\Views\Twig;

class ValidationErrorsMiddleware {

    private $view;
    
    public function __construct(Twig $view) {
        $this->view = $view;
    }

    public function __invoke($request, $response, $next) {
        $errors = [];
        if(isset($_SESSION['errors'])){
            $errors = $_SESSION['errors'];
            unset($_SESSION['errors']);
        }
        $this->view->getEnvironment()->addGlobal('errors', $errors);

        $response = $next($request, $response);
        return $response;
    }

}

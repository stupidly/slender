<?php declare(strict_types=1);

namespace App\Middleware;

use Slim\Views\Twig;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class OldInputMiddleware{

    protected $view;

    public function __construct(Twig $view){
        $this->view = $view;
    }
    
    public function __invoke($request, $response, $next) {
        if(isset($_SESSION['old'])){
            $this->view->getEnvironment()->addGlobal('old', $_SESSION['old']);
        }
        $_SESSION['old'] = $request->getParams();
        return $next($request, $response);
    }
}
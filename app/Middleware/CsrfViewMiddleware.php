<?php declare(strict_types=1);

namespace App\Middleware;

use App\Auth\Auth;
use Slim\Csrf\Guard as CsrfMiddleware;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\Twig;

class CsrfViewMiddleware{

    protected $csrf;
    protected $view;

    public function __construct(Twig $view, CsrfMiddleware $csrf){
        $this->csrf = $csrf;
        $this->view = $view;
    }
    
    public function __invoke($request, $response, $next) {
        $this->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="' . $this->csrf->getTokenNameKey() . '" value="' . $this->csrf->getTokenName() . '">
                <input type="hidden" name="' . $this->csrf->getTokenValueKey() . '" value="' . $this->csrf->getTokenValue() . '">
            ',
            'name_key' => $this->csrf->getTokenNameKey(),
            'value_key' => $this->csrf->getTokenValueKey(),
            'name' => $this->csrf->getTokenName(),
            'value' => $this->csrf->getTokenValue(),
        ]);
        return $next($request, $response);
    }
}
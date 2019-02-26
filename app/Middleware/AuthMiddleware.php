<?php declare(strict_types=1);

namespace App\Middleware;

use App\Auth\Auth;
use Slim\Router;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class AuthMiddleware{
	
    protected $auth;
    protected $redirectPath;
    protected $router;

    public function __construct(Auth $auth, Router $router)
    {
        $this->auth = $auth;
        $this->router = $router;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        try{
            $this->auth->authenticate($request);
        }catch(\Exception $e){
            if($request->getUri()->getPath() !== $this->redirectPath){
                $this->redirectPath .= '?redirect-url=' . $request->getUri()->getPath();
            }
            return $response->withRedirect($this->redirectPath);
        }
        return $next($request, $response);
    }

    public function withRedirectPathName($redirectPathName){
        $this->redirectPath = $this->router->pathFor($redirectPathName);
        return $this;
    }
}
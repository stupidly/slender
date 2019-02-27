<?php declare(strict_types=1);

namespace App\Auth\Middleware;

use App\Auth\Auth;
use Slim\Router;
use Slim\Views\Twig;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class AuthMiddleware{
	
    protected $auth;
    protected $redirectPath;
    protected $router;
    protected $view;
    protected $roles;

    public function __construct(Auth $auth, Router $router, Twig $view)
    {
        $this->auth = $auth;
        $this->router = $router;
        $this->view = $view;
        $this->roles = [Auth::GUEST];
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        try{
            $this->auth->authenticate($request);
            $this->view->getEnvironment()->addGlobal('auth', [
                'user' => $this->auth->getUser(),
            ]);
        }catch(\Exception $e){
            if($request->getUri()->getPath() !== $this->redirectPath){
                $this->redirectPath .= '?redirect-url=' . $request->getUri()->getPath();
            }
            if(in_array(Auth::GUEST, $this->roles)){
                return $next($request, $response);
            }else{
                return $response->withRedirect($this->redirectPath);
            }
        }
        return $next($request, $response);
    }

    public function withRedirectPathName($redirectPathName){
        $this->redirectPath = $this->router->pathFor($redirectPathName);
        return $this;
    }

    public function withRoles(array $roles){
        $this->roles = $roles;
        return $this;
    }
}
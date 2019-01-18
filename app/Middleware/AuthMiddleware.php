<?php declare(strict_types=1);

namespace App\Middleware;

use App\Auth\Auth;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class AuthMiddleware{
	
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        try{
            $this->auth->authenticate($request);
        }catch(\Exception $e){
            return $response->withJson([
                'message' => $e->getMessage()
            ], 401);
        }
        return $next($request, $response);
    }
}
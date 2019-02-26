<?php declare(strict_types=1);

namespace App\Auth\Jwt;

use App\Auth\Auth;
use App\Auth\AuthRepositoryInterface as AuthRepository;
use App\Auth\Jwt\JwtLibInterface;
use App\Auth\Jwt\JwtSubjectInterface;
use Carbon\Carbon;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class JwtAuth extends Auth{

    protected $jwtLib;
    protected $repo;
    protected $token;

    public function __construct(JwtLibInterface $jwtLib, AuthRepository $repo){
        $this->jwtLib = $jwtLib;
        $this->repo = $repo;
    }

    public function attemptCredentials(String $username, String $password){
    	if(!$user = $this->repo->byCredentials($username, $password)){
            throw new Exception('No such user');
        }

        $this->token = $this->fromSubject($user);
        $this->setCookie();
        return $user;
    }

    public function authenticate(Request $request){
        $this->authenticateFromCookie($request);
    }

    protected function authenticateFromCookie(Request $request){
        if (!$this->token = $this->getTokenFromCookie($request)) {
            throw new Exception('No authentication cookie');
        }
        try {
            $this->user = $this->repo->byId($this->jwtLib->decode($this->token));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected function authenticateFromHeader(Request $request){
        if (!$header = $this->getAuthorizationHeader($request)) {
            throw new Exception('No authentication header');
        }
        try {
            $this->token = $this->extractToken($header);
            $this->user = $this->repo->byId($this->jwtLib->decode($this->token));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

	public function check() : bool{
		return false;
	}

    public function getRole() : string{
    	return Auth::GUEST;
    }

    public function logout(Request $request){
        $this->token = null;
        setcookie($this->container->get('settings')->get('jwt')['cookieName'], "", time()-3600);
    }

    public function signUp(String $username, String $password, $role){
        try{
            $user = $this->repo->register($username, $password, $role);
            return $user;
        }catch(\Exception $e){
            return null;
        }
    }

    protected function fromSubject(JwtSubjectInterface $subject){
        return $this->initToken()
        ->withSubject($subject->getJwtSubject())
        ->encode();
    }

    protected function initToken() : JwtLibInterface{
        return $this->jwtLib->create()
        ->withSubject("")
        ->withId(bin2hex(str_random(32)))
        ->withIssuer($this->getHost())
        ->withIsuedAt(Carbon::now()->getTimestamp())
        ->withNotBefore(Carbon::now()->getTimestamp())
        ->withExpiration(Carbon::now()->addMinutes($this->container->get('settings')->get('jwt.expiry'))->getTimestamp());
    }

    protected function getAuthorizationHeader(Request $request)
    {
        if (!$request->hasHeader('Authorization') || !list($header) = $request->getHeader('Authorization', false)) {
            return false;
        }

        return $header;
    }

    protected function extractToken($header) : string
    {
        if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function getTokenFromCookie(Request $request){
        return $request->getCookieParam($this->container->get('settings')->get('jwt')['cookieName']);
    }

    protected function setCookie(){
        setcookie(
            $this->container->get('settings')->get('jwt')['cookieName'],
            $this->token,
            time() + $this->container->get('settings')->get('jwt')['expiry'] * 60,
            "",
            "",
            false, //secure: TRUE
            true //httponly
        );
    }
}
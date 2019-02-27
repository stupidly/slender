<?php declare(strict_types=1);

namespace App\Auth\Jwt;

use App\Auth\Auth;
use App\Auth\AuthRepositoryInterface as AuthRepository;
use App\Auth\Jwt\JwtLibInterface;
use App\Auth\Jwt\JwtSubjectInterface;
use Carbon\Carbon;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class JwtAuth extends Auth{

    protected $jwtLib;
    protected $log;
    protected $repo;
    protected $token;
    protected $user;

    public function __construct(JwtLibInterface $jwtLib, AuthRepository $repo, LoggerInterface $log){
        $this->jwtLib = $jwtLib;
        $this->repo = $repo;
        $this->log = $log;
    }

    public function attemptCredentials(String $username, String $password){
    	if(!$user = $this->repo->byCredentials($username, $password)){
            throw new Exception('No such user');
        }

        $this->token = $this->fromSubject($user);
        $this->user = $user;
        $this->setCookie();
        $_SESSION['user'] = $this->user->username;
        $this->log->info('User logged in', ['user'=>$this->user->username]);
        return $user;
    }

    public function authenticate(Request $request){
        $this->authenticateFromCookie($request);
        if(in_array('user', $_SESSION)){
            if($_SESSION['user'] !== $this->user->username){
                $this->log->warning('Cookie and session mismatch!', ['cookie'=>$this->user->username, 'session'=>$_SESSION['user']]);
                $this->logout($request);
                throw new Exception('Cookie and session mismatch!');
            }
        }else{
            $_SESSION['user'] = $this->user->username;
        }
    }

    protected function authenticateFromCookie(Request $request){
        if (!$this->token = $this->getTokenFromCookie($request)) {
            throw new Exception('No authentication cookie');
        }
        try {
            $this->user = $this->repo->byId($this->jwtLib->decode($this->token)['sub']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getUser(){
        return $this->user;
    }

    public function getRole() : string{
    	return Auth::GUEST;
    }

    public function logout(Request $request){
        $this->log->info('User logged out', ['user'=>$this->user->username]);
        setcookie($this->container->get('settings')->get('jwt')['cookieName'], "", time()-3600);
        $this->token = null;
        $this->user = null;
        session_destroy();
    }

    public function signUp(String $username, String $password, String $role){
        try{
            $this->repo->register($username, $password, $role);
            return $this->attemptCredentials($username, $password);
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
            $this->container->get('settings')->get('app')['ssl'],
            true //httponly
        );
    }
}
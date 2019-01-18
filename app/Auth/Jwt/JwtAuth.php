<?php declare(strict_types=1);

namespace App\Auth\Jwt;

use App\Auth\Auth;
use App\Auth\AuthRepositoryInterface as AuthRepository;
use App\Auth\Jwt\JwtLibInterface;
use App\Auth\Jwt\JwtSubjectInterface;
use Carbon\Carbon;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;

class JwtAuth extends Auth{

    protected $jwtLib;
    protected $repo;

    public function __construct(JwtLibInterface $jwtLib, AuthRepository $repo){
        $this->jwtLib = $jwtLib;
        $this->repo = $repo;
    }

    public function attemptCredentials(String $username, String $password) : string{
    	if(!$user = $this->repo->byCredentials($username, $password)){
            return false;
        }

        return $this->fromSubject($user);
    }

    public function authenticate(Request $request){
        if (!$header = $this->getAuthorizationHeader($request)) {
            throw new Exception('No authentication header');
        }

        try {
            $token = $this->extractToken($header);
            $this->user = $this->repo->byId($this->jwtLib->decode($token));
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

    public function logout(){

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

    protected function getAuthorizationHeader(Request $request) : string
    {
        if (!list($header) = $request->getHeader('Authorization', false)) {
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
	
}
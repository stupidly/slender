<?php declare(strict_types=1);

namespace App\Auth;

use App\Auth\AuthRepositoryInterface as AuthRepository;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Auth implements ContainerAwareInterface{

	use ContainerAwareTrait;

    const GUEST = "guest";
    const USER = "user";
    const ADMIN = "admin";

    protected $user;

    public function getHost(){
    	return $this->container->get('request')->getServerParams()['HTTP_HOST'];
    }

    public function isAllowed(array $allowedUsers) {
        if (empty($allowedUsers)) {
            return true;
        } else {
            return in_array($this->getRole(), $allowedUsers);
        }
    }

    public abstract function attemptCredentials(String $username, String $password);

    public abstract function authenticate(Request $request);

    public abstract function logout(Request $request);

    public abstract function getRole() : string;

    public abstract function getUser();

    public abstract function signUp(String $username, String $password, String $role);
}
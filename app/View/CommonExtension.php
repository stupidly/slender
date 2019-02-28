<?php declare(strict_types=1);

namespace App\View;

use App\Auth\Auth;
use Slim\Http\Request;
use Slim\Views\TwigExtension;

class CommonExtension extends TwigExtension {

	protected $auth;

	public function __construct(Auth $auth){
		$this->auth = $auth;
	}

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('user_in', [$this, 'isAllowed']),
        ];
    }

    public function isAllowed(array $allowedRoles) {
    	if($this->auth->getUser() === null){
    		return in_array(Auth::GUEST, $allowedRoles);
    	}else{
    		return in_array($this->auth->getUser()->role, $allowedRoles);
    	}
    }
}
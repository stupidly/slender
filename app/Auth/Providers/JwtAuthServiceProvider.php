<?php declare(strict_types=1);

namespace App\Auth\Providers;

use App\Auth\Auth;
use App\Auth\Jwt\JwtAuth;
use League\Container\ServiceProvider\AbstractServiceProvider;

class JwtAuthServiceProvider extends AbstractServiceProvider{

	protected $provides = [
		Auth::class
	];

	public function register(){
		$container = $this->getContainer();
		$container->share(Auth::class, function () use ($container){
			$auth = $container->get(JwtAuth::class);
			return $auth;
		});
	}
	
}
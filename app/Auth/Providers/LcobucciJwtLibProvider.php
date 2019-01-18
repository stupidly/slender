<?php declare(strict_types=1);

namespace App\Auth\Providers;

use App\Auth\Jwt\JwtLibInterface;
use App\Auth\Jwt\LcobucciJwtLib;
use League\Container\ServiceProvider\AbstractServiceProvider;

class LcobucciJwtLibProvider extends AbstractServiceProvider{

	protected $provides = [
		JwtLibInterface::class
	];

	public function register(){
		$container = $this->getContainer();
		$container->share(JwtLibInterface::class, function () use ($container){
			// $lib = $container->get(LcobucciJwtLib::class);
			$lib = new LcobucciJwtLib($container);
			return $lib;
		});
	}
	
}
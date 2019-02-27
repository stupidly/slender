<?php declare(strict_types=1);

namespace App\Providers;

use App\Validation\Validator;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Respect\Validation\Validator as v;

class ValidationServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface{

	protected $provides = [
		Validator::class
	];

	public function boot(){
		v::with('App\\Validation\\Rules\\');
	}

	public function register(){
		$container = $this->getContainer();
		$container->share(Validator::class, function () use ($container){
			$validator = new Validator();
			return $validator;
		});
	}
}
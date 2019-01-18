<?php declare(strict_types=1);

namespace App\Auth\Providers;

use App\Auth\AuthRepositoryInterface;
use App\Auth\Eloquent\EloquentAuthRepository;
use League\Container\ServiceProvider\AbstractServiceProvider;

class EloquentAuthRepositoryProvider extends AbstractServiceProvider{

	protected $provides = [
		AuthRepositoryInterface::class
	];

	public function register(){
		$container = $this->getContainer();
		$container->share(AuthRepositoryInterface::class, function () use ($container){
			$repo = new EloquentAuthRepository();
			return $repo;
		});
	}
}
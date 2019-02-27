<?php declare(strict_types=1);

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\Csrf\Guard as CsrfMiddleware;

class CsrfServiceProvider extends AbstractServiceProvider{

	protected $provides = [
		CsrfMiddleware::class
	];

	public function register(){
		$container = $this->getContainer();
		$container->share(CsrfMiddleware::class, function () use ($container){
			$csrf = new CsrfMiddleware();
            $csrf->setPersistentTokenMode(true);
            $csrf->setFailureCallable(function($request,$response){
                throw new \Error("Csrf error");
            });
			return $csrf;
		});
	}
}
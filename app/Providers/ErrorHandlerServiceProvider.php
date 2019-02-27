<?php declare(strict_types=1);

namespace App\Providers;

use App\Auth\Auth;
use App\Handlers\NotAllowedErrorHandler;
use App\Handlers\NotFoundErrorHandler;
use App\Handlers\ProductionErrorHandler;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Log\LoggerInterface;
use Slim\Csrf\Guard as CsrfMiddleware;
use Slim\Flash\Messages;
use Slim\Router;
use Symfony\Component\Translation\TranslatorInterface;

class ErrorHandlerServiceProvider extends AbstractServiceProvider{

	protected $provides = [
		'phpErrorHandler',
		'notFoundHandler',
		'notAllowedHandler'
	];

	public function register(){
		$container = $this->getContainer();
		$container->share('phpErrorHandler', function () use ($container){
			return new ProductionErrorHandler(
				$container->get(LoggerInterface::class), 
				$container->get(Router::class), 
				$container->get(TranslatorInterface::class), 
				$container->get(Messages::class),
				$container->get(Auth::class)
			);
		});
		$container->share('notFoundHandler', function () use ($container){
			return new NotFoundErrorHandler(
				$container->get(LoggerInterface::class), 
				$container->get(Router::class), 
				$container->get(TranslatorInterface::class), 
				$container->get(Messages::class),
				$container->get(Auth::class)
			);
		});
		$container->share('notAllowedHandler', function () use ($container){
			return new NotAllowedErrorHandler(
				$container->get(LoggerInterface::class), 
				$container->get(Router::class), 
				$container->get(TranslatorInterface::class), 
				$container->get(Messages::class),
				$container->get(Auth::class)
			);
		});
	}
}
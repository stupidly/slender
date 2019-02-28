<?php declare(strict_types=1);

namespace App\Providers;

use App\View\CommonExtension;
use App\View\TranslateExtension;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\Flash\Messages;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Router;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

class ViewServiceProvider extends AbstractServiceProvider{

	protected $provides = [
		Twig::class,
		Messages::class,
	];

	public function register(){
		$container = $this->getContainer();
		$container->share(Messages::class, function () use ($container){
			$messages = new Messages();
			return $messages;
		});
		$container->share(Twig::class, function () use ($container){
			$view = new Twig(__DIR__ . '/../../resources/views', [
				'cache' => $container->get('settings')->get('views.cache')
			]);

			$uri = Uri::createFromEnvironment(new Environment($_SERVER));
			$view->addExtension(new TwigExtension($container->get(Router::class), $uri));
			$view->addExtension($container->get(TranslateExtension::class));
			$view->addExtension($container->get(CommonExtension::class));
            $view->getEnvironment()->addGlobal("messages", $container->get(Messages::class));
			return $view;
		});
	}
}
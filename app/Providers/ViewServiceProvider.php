<?php declare(strict_types=1);

namespace App\Providers;

use App\View\TranslateExtension;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

class ViewServiceProvider extends AbstractServiceProvider{

	protected $provides = [
		'view',
		Messages::class,
	];

	public function register(){
		$container = $this->getContainer();
		$container->share(Messages::class, function () use ($container){
			$messages = new Messages();
			return $messages;
		});
		$container->share('view', function () use ($container){
			$view = new Twig(__DIR__ . '/../../resources/views', [
				'cache' => $container->get('settings')->get('views.cache')
			]);

			$basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
			$view->addExtension(new TwigExtension($container->get('router'), $basePath));
			$view->addExtension($container->get(TranslateExtension::class));
            $view->getEnvironment()->addGlobal("messages", $container->get(Messages::class));
			return $view;
		});
	}
}
<?php

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

class ViewServiceProvider extends AbstractServiceProvider{

	protected $provides = [
		'view'
	];

	public function register(){
		$container = $this->getContainer();
		$container->share('view', function () use ($container){
			$view = new Twig(__DIR__ . '/../../resources/views', [
				'cache' => $container->get('settings')->get('views.cache')
			]);

			$basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
			$view->addExtension(new TwigExtension($container->get('router'), $basePath));

			return $view;
		});
	}
}
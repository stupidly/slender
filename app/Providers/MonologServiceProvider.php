<?php declare(strict_types=1);

namespace App\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class MonologServiceProvider extends AbstractServiceProvider{

	protected $provides = [
		LoggerInterface::class
	];

	public function register(){
		$container = $this->getContainer();
		$container->share(LoggerInterface::class, function () use ($container){
			$logger = new Logger("app");
			$logConfig = $container->get('settings')->get('log');
			$fileHandler = new StreamHandler($logConfig['path'], $logConfig['level']);
			$formatter = new LineFormatter($logConfig['outputFormat'], $logConfig['dateFormat']);
			$fileHandler->setFormatter($formatter);
			$logger->pushHandler($fileHandler);
			if($container->get('settings')->get('app.debug')){
				$logger->pushHandler(new FirePHPHandler());
			}
			return $logger;
		});
	}
}
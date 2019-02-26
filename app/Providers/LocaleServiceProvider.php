<?php declare(strict_types=1);

namespace App\Providers;

use App\View\TranslateExtension;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class LocaleServiceProvider extends AbstractServiceProvider{

	protected $provides = [
		TranslatorInterface::class,
		TranslateExtension::class,
	];

	public function register(){
		$container = $this->getContainer();
		$container->share(TranslatorInterface::class, function () use ($container){
			$defaultLocale = $container->get('settings')->get('app')['locale'];
			$translator = new Translator($defaultLocale);
			$translator->setFallbackLocales([$defaultLocale]);
			$translator->addLoader("php", new PhpFileLoader());

			$finder = new Finder();
			$langDirs = $finder->directories()->ignoreUnreadableDirs()->in(__DIR__ . "/../../resources/lang");
			foreach ($langDirs as $dir) {
				$files = (new Finder())->files()->in($dir->getRealPath());
				foreach ($files as $file) {
					$translator->addResource("php", $file, $dir->getRelativePathName());
				}
			}
			return $translator;
		});
		$container->share(TranslateExtension::class, function () use ($container){
			return new TranslateExtension($container->get(TranslatorInterface::class));
		});
	}
}
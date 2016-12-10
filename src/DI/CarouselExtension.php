<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @author      Pavel Janda <me@paveljanda.com>
 * @package     Wunderman\CMS\Carousel
 */

namespace Wunderman\CMS\Carousel\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\Arrays;
use Nette\Application\IPresenterFactory;

class CarouselExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$extensionConfig = $this->loadFromFile(__DIR__ . '/config.neon');
		$this->compiler->parseServices($builder, $extensionConfig, $this->name);

		$builder->parameters = Arrays::mergeTree($builder->parameters,
			Arrays::get($extensionConfig, 'parameters', []));

		$presenterFactory = $builder->getDefinition('application.presenterFactory');
		$presenterFactory->addSetup('setMapping', [['CMSCarousel' => 'Wunderman\CMS\Carousel\*Module\Presenter\*Presenter']]);

		$router = $builder->getDefinition('routing.router');
		$router->addSetup('$service->prepend(new Nette\Application\Routers\Route(?, ?))', ['administration/extension/carousel/<presenter>', [
			'module' => 'CMSCarousel:Private',
			'presenter' => 'List',
			'action' => 'default',
		]]);
	}


	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		$builder->getDefinition('privateComposePresenter')->addSetup(
			'addExtensionService',
			['carousel', $this->prefix('@carouselService')]
		);

		/**
		 * Extending ACL resources
		 */
		$builder->getDefinition('authorizator')->addSetup('addResource', ['CMSCarousel:Private:List']);
		$builder->getDefinition('authorizator')->addSetup('addResource', ['CMSCarousel:Private:New']);
		$builder->getDefinition('authorizator')->addSetup('addResource', ['CMSCarousel:Private:Edit']);

		/**
		 * PublicModule component
		 */
		$builder->getDefinition('publicComposePresenter')->addSetup(
			'setComposeComponentFactory',
			['carousel', $this->prefix('@publicCarouselFactory')]
		);

		/**
		 * PrivateModule component
		 */
		$builder->getDefinition('privateComposePresenter')->addSetup(
			'setComposeComponentFactory',
			['carousel', $this->prefix('@privateCarouselFactory')]
		);

		$builder->getDefinition($builder->getByType(IPresenterFactory::class))->addSetup(
			'setMapping',
			[['Carousel' => 'Wunderman\CMS\Carousel\*Module\Presenter\*Presenter']]
		);
	}

}

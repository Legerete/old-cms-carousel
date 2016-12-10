<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @package     Carousel
 */

namespace Wunderman\CMS\Carousel\PrivateModule\Components\Carousel;

use Kdyby\Doctrine\EntityManager;
use Wunderman\CMS\Carousel\PublicModule;

class Carousel extends PublicModule\Components\Carousel\Carousel
{

	/**
	 * @var array
	 */
	protected $componentParams;


	public function __construct(array $componentParams = [], EntityManager $em)
	{
		parent::__construct($em);

		$this->componentParams = $componentParams;
	}


	/**
	 * @var int $id
	 */
	public function render($id = null)
	{
		$id = (int) $this->componentParams[0]->value;

		parent::render($id);
	}

}

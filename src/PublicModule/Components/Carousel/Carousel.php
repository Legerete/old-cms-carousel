<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @package     Wunderman\CMS\Carousel
 */

namespace Wunderman\CMS\Carousel\PublicModule\Components\Carousel;

use Kdyby\Doctrine\EntityManager;

class Carousel extends \Nette\Application\UI\Control
{

	/**
	 * @var EntityManager
	 */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	/**
	 * @var integer $id
	 */
	public function render($id)
	{
		$this->getTemplate()->id = $id;
		$this->getTemplate()->carousel = $this->carouselRepository()->find($id);

		$this->getTemplate()->render(__DIR__.'/templates/Carousel.latte');
	}


	public function carouselRepository()
	{
		return $this->em->getRepository(\Wunderman\CMS\Carousel\Entity\Carousel::class);
	}

}

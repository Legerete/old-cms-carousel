<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @package     Wunderman\CMS\Carousel
 */

namespace Wunderman\CMS\Carousel\PublicModule\Components\Carousel;

interface ICarouselFactory
{

	/**
	 * @return Carousel
	 */
	public function create();

}

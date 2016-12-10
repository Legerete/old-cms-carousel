<?php

namespace Wunderman\CMS\Carousel\PrivateModule\Components\Carousel;

interface ICarouselFactory
{

	/**
	 * @return Carousel
	 * @param  array $componentParams
	 */
	public function create(array $componentParams);

}

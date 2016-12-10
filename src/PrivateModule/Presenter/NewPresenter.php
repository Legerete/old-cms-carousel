<?php

namespace Wunderman\CMS\Carousel\PrivateModule\Presenter;

use Wunderman\CMS\Carousel\PrivateModule\Model\CarouselModel;

class NewPresenter extends \App\PrivateModule\PrivatePresenter
{
	/**
	 * @inject
	 * @var \Tracy\ILogger
	 */
	public $logger;

	/**
	 * @inject
	 * @var CarouselModel
	 */
	public $carouselModel;

	public function actionDefault()
	{
		$carousel = $this->carouselModel->createNewCarousel();
		$this->redirect(':CMSCarousel:Private:Edit:', array('id' => $carousel->id));
	}
}

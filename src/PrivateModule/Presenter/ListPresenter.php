<?php

namespace Wunderman\CMS\Carousel\PrivateModule\Presenter;

use Wunderman\CMS\Carousel\PrivateModule\Model\CarouselModel;

class ListPresenter extends \App\PrivateModule\PrivatePresenter
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

	public function renderDefault()
	{
		$this->getTemplate()->carousels = $this->carouselModel->readCarousels();
	}

	public function handleRemoveItem($item)
	{
		try {
			$this->carouselModel->destroyCarousel($item);
		    $this->flashMessage('Carousel has been deleted.', 'success');
		} catch (\Exception $e) {
		    $this->logger->log($e);
			$this->flashMessage('Carousel cannot be deleted. Error was logged.', 'danger');
		}
	}
}

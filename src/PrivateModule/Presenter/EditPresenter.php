<?php

namespace Wunderman\CMS\Carousel\PrivateModule\Presenter;


use Wunderman\CMS\Carousel\PrivateModule\Model\CarouselModel;
use Nette\Application\UI\Form;
use Wunderman\CMS\Carousel\PrivateModule\Components\EditItemForm\EditItemForm;

class EditPresenter extends \App\PrivateModule\PrivatePresenter
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

	/**
	 * @var \App\Entity\Carousel
	 */
	public $carousel;

	/**
	 * @var \App\Entity\CarouselItem
	 */
	private $item;

	/**
	 * @inject
	 * @var \App\PrivateModule\AttachmentModule\Model\Service\AttachmentService
	 */
	public $attachmentService;

	/**
	 * @param $id
	 */
	public function renderDefault($id)
	{
		$this->getTemplate()->carousel = $this->carousel = $this->carouselModel->readCarousel($id);
	}

	/**
	 * @return Form
	 */
	public function createComponentBoxPreferencesForm()
	{
		$this->getCarousel();
		$form = new Form();
		$form->addSubmit('save', 'Save');
		$form->addText('name', 'Name')->setDefaultValue($this->carousel->name === 'newBox' ?: $this->carousel->name);
		$form->addCheckbox('showNavigation', 'Show navigation')->setDefaultValue($this->carousel->showNavigation);
		$form->addCheckbox('showHeader', 'Show navigation')->setDefaultValue($this->carousel->showHeader);

		$form->onSuccess[] = array($this, 'editPreferences');

		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function editPreferences(Form $form)
	{
		$values = $form->getValues();
		$this->carousel->setName($values->name)->setShowNavigation($values->showNavigation)->setShowHeader($values->showHeader);
		$this->em->flush();

		$this->flashMessage('Carousel was saved.', 'success');

		if ($this->isAjax())
		{
			$this->redrawControl('carouselEditForm');
			$this->redrawControl('flashes');
		}
		else
		{
			$this->redirect('this');
		}
	}

	/**
	 * @return void
	 */
	public function handleEditCarouselItem()
	{
		$this->item = $this->carouselModel->readCarouselItem($this->getParameter('editItemId'));
		$this->redirect('this', ['editItemId' => $this->getParameter('editItemId')]);
	}

	/**
	 * @return void
	 */
	public function handleMoveRight()
	{
		$item = $this->carouselModel->readCarouselItem($this->getParameter('moveItemId'));
		$item->setPosition($item->position +1);
		$this->em->flush();

		if ($this->isAjax())
		{
			$this->redrawControl('editFormItem');
		}
	}

	/**
	 * @return void
	 */
	public function handleMoveLeft()
	{
		$item = $this->carouselModel->readCarouselItem($this->getParameter('moveItemId'));
		$item->setPosition($item->position -1);
		$this->em->flush();

		if ($this->isAjax())
		{
			$this->redrawControl('editFormItem');
		}
	}

	/**
	 * @return EditItemForm
	 */
	public function createComponentEditItem()
	{
		$this->getCarousel();
		$component = new EditItemForm($this->attachmentService, $this->carouselModel, $this->carousel, $this->getParameter('editItemId'));
		$component->onSuccess[] = [$this, 'redirectWithEditItem'];
		return $component;
	}

	/**
	 * @return EditItemForm
	 */
	public function createComponentNewItem()
	{
		$this->getCarousel();
		$component =  new EditItemForm($this->attachmentService, $this->carouselModel, $this->carousel);
		return $component;
	}

	public function redirectWithEditItem($editItemId)
	{
		$this->redirect('this', ['editItemId' => $editItemId]);
	}

	private function getCarousel()
	{
		if (!$this->carousel) {
			$this->carousel = $this->carouselModel->readCarousel($this->getHttpRequest()->getQuery('id'));
		}
	}
}

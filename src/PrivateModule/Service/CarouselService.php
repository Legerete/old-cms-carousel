<?php

/**
 * @copyright   Copyright (c) 2016 Wunderman s.r.o. <wundermanprague@wunwork.cz>
 * @author      Petr Besir Horáček <sirbesir@gmail.com>
 * @package     Wunderman\CMS\Carousel
 */

namespace Wunderman\CMS\Carousel\PrivateModule\Service;


use App\PrivateModule\AttachmentModule\Model\Service\AttachmentService;
use App\PrivateModule\PagesModule\Presenter\IExtensionService;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Form;
use Nette\Http\Request;
use Nette\Utils\ArrayHash;
use Wunderman\CMS\Carousel\Entity\Carousel;

class CarouselService implements IExtensionService
{

	/**
	 * @var AttachmentService
	 */
	private $attachmentService;

	/**
	 * @var Request
	 */
	private $httpRequest;

	/**
	 * @type EntityManager
	 */
	private $em;

	/**
	 * Service constructor.
	 *
	 * @param AttachmentService $attachmentService
	 * @param Request $httpRequest
	 * @param EntityManager $em
	 */
	public function __construct(AttachmentService $attachmentService, Request $httpRequest, EntityManager $em)
	{
		$this->attachmentService = $attachmentService;
		$this->httpRequest = $httpRequest;
		$this->em = $em;
	}

	/**
	 * Prepare adding new item, add imputs to global form etc.
	 *
	 * @param Form $form
	 *
	 * @return mixed
	 */
	public function addItem(Form $form)
	{
		if (isset($form[self::ITEM_CONTAINER])) {
			unset($form[self::ITEM_CONTAINER]);
		}
		$form->addContainer(self::ITEM_CONTAINER);

		$item = $form->getComponent(self::ITEM_CONTAINER);

		$item->addSelect('carouselId', 'Choose an carousel', $this->readCarousels());

		$item->addHidden('type')->setValue('carousel');
		$item->addHidden('itemId');
	}

	/**
	 * @param Form $form
	 * @param array $editItem
	 *
	 * @return mixed
	 */
	public function editItemParams(Form $form, $editItem)
	{
		$params = $this->createParamsAssocArray($editItem->params);

		$this->addItem($form);


		$form[self::ITEM_CONTAINER]->setDefaults([
			'itemId' => $editItem->id,
			'carouselId' => $params['id']
		]);
	}

	/**
	 * Make magic for creating new item, e.g. save new image and return his params for save.
	 * @var Form $form
	 * @var ArrayHash $values Form values
	 * @return array Associated array in pair [ propertyName => value ] for store to the database
	 */
	public function processNew(Form $form, ArrayHash $values)
	{
		return ['id' => $values['carouselId']];
	}

	/**
	 * Editing current edited item
	 * @var Form $form
	 * @var ArrayHash $values Form values
	 * @var array $itemParams
	 * @return array
	 */
	public function processEdit(Form $form, ArrayHash $values, $itemParams)
	{
		return ['id' => $values['carouselId']];
	}

	/**
	 * Compute anchor for item on the page
	 * @var object
	 * @return string
	 */
	public function getAnchor($item)
	{
		$params = $this->createParamsAssocArray($item->params);
		$carousel = $this->carouselRepository()->find($params['id']);
		return $carousel ? \Nette\Utils\Strings::webalize($carousel->name) : false;
	}

	/**
	 * @return string
	 */
	public function getAddItemTemplate()
	{
		return realpath(__DIR__ . '/../templates/editItem.latte');
	}

	/**
	 * @return string
	 */
	public function getEditItemTemplate()
	{
		return $this->getAddItemTemplate();
	}


	/**
	 * @param $params
	 *
	 * @return array
	 */
	private function createParamsAssocArray($params)
	{
		$assocParams = [];
		foreach ($params as $param) {
			$assocParams[$param->name] = $param->value;
		}

		return $assocParams;
	}

	/**
	 * @return array
	 */
	public function readCarousels()
	{
		return $this->em->getRepository(Carousel::class)->findPairs(array('status' => 'ok'), 'name', array(), 'id');
	}

	/**
	 * @return \Kdyby\Doctrine\EntityRepository
	 */
	public function carouselRepository()
	{
		return $this->em->getRepository(Carousel::class);
	}
}

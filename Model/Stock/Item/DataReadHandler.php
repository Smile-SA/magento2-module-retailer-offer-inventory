<?php
/**
 * Model Stock Item Handler
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Model\Stock\Item;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Smile\Offer\Model\Offer;
//use Smile\RetailerOfferInventory\Model\Stock\Item;
use Smile\RetailerOfferInventory\Model\Stock\ItemFactory as StockItemFactory;
use Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item as StockItemResource;

/**
 * Class DataReadHandler
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class DataReadHandler implements ExtensionInterface
{
    /**
     * @var StockItemFactory
     */
    private $stockItemFactory;

    /**
     * @var StockItemResource
     */
    private $stockItemResource;

    public function __construct(
        StockItemFactory $stockItemFactory,
        StockItemResource $stockItemResource
    ) {
        $this->stockItemFactory = $stockItemFactory;
        $this->stockItemResource = $stockItemResource;
    }

    /**
     * Perform action on relation/extension attribute
     *
     * @param Offer|object $entity
     * @param array  $arguments
     *
     * @return object|bool
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @throws \Exception
     */
    public function execute($entity, $arguments = [])
    {
        /** @var OfferData $offerData */
        $offerData = $this->stockItemFactory->create();
        $this->stockItemResource->load($offerData, $entity->getId(), 'offer_id');
        $attributes = $entity->getExtensionAttributes() ?: [];
        $attributes['offer_stock'] = $offerData->getData();
        $entity->setExtensionAttributes($attributes);

        return $entity;
    }
}

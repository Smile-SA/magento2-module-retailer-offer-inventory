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

/**
 * Class ReadHandler
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class ReadHandler implements \Magento\Framework\EntityManager\Operation\ExtensionInterface
{
    /**
     * @var \Smile\RetailerOfferInventory\Model\Stock\ItemFactory
     */
    private $stockItemFactory;

    /**
     * @var \Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item
     */
    private $stockItemResource;

    /**
     * ReadHandler constructor.
     *
     * @param \Smile\RetailerOfferInventory\Model\Stock\ItemFactory        $stockItemFactory  Stock item factory.
     * @param \Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item $stockItemResource Stock item resource.
     */
    public function __construct(
        \Smile\RetailerOfferInventory\Model\Stock\ItemFactory $stockItemFactory,
        \Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item $stockItemResource
    ) {
        $this->stockItemFactory  = $stockItemFactory;
        $this->stockItemResource = $stockItemResource;
    }

    /**
     * Perform action on relation/extension attribute
     *
     * @param \Smile\Offer\Api\Data\OfferInterface|object $entity    Entity
     * @param array                                       $arguments Arguments
     *
     * @return object|bool
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @throws \Exception
     */
    public function execute($entity, $arguments = [])
    {
        $stockItem = $this->stockItemFactory->create();
        $this->stockItemResource->load($stockItem, $entity->getId(), 'offer_id');
        $entity->getExtensionAttributes()->setStockItem($stockItem);

        return $entity;
    }
}

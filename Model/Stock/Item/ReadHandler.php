<?php

/**
 * Model Stock Item Handler
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Model\Stock\Item;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item;
use Smile\RetailerOfferInventory\Model\Stock\ItemFactory;

/**
 * ReadHandler model class
 *
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * ReadHandler constructor.
     *
     * @param \Smile\RetailerOfferInventory\Model\Stock\ItemFactory        $stockItemFactory  Stock item factory.
     * @param \Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item $stockItemResource Stock item resource.
     */
    public function __construct(
        private ItemFactory $stockItemFactory,
        private Item $stockItemResource
    ) {
    }

    /**
     * Perform action on relation/extension attribute
     *
     * @param object $entity    Entity
     * @param array                                       $arguments Arguments
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @throws \Exception
     */
    public function execute($entity, $arguments = []): object|bool
    {
        $stockItem = $this->stockItemFactory->create();
        $this->stockItemResource->load($stockItem, $entity->getId(), 'offer_id');
        $entity->getExtensionAttributes()->setStockItem($stockItem);

        return $entity;
    }
}

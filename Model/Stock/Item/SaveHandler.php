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
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;
use Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item;
use Smile\RetailerOfferInventory\Model\Stock\ItemFactory;

/**
 * Model SaveHandler class
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * ReadHandler constructor.
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
        $offerData = $entity->getInventory() ?? [];

        if (!empty($offerData)) {
            $offerData[StockItemInterface::FIELD_OFFER_ID] = $entity->getId();
            $stockItem = $this->stockItemFactory->create();
            if ($entity->getExtensionAttributes()->getStockItem()) {
                $stockItem = $entity->getExtensionAttributes()->getStockItem();
            }

            $stockItem->addData($offerData);
            $this->stockItemResource->save($stockItem);
        }

        return $entity;
    }
}

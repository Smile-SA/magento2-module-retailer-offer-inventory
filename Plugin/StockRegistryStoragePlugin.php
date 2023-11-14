<?php

/**
 * Plugin StockRegistryStoragePlugin
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Plugin;

use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Model\StockRegistryStorage;
use Smile\RetailerOffer\Helper\Settings;
use Smile\RetailerOfferInventory\Helper\OfferInventory;

/**
 * StockRegistryStoragePlugin class on \Magento\CatalogInventory\Model\StockRegistryStorage
 */
class StockRegistryStoragePlugin
{
    /**
     * StockItemPlugin constructor.
     */
    public function __construct(
        private Settings $settingsHelper,
        private OfferInventory $offerInventoryHelper
    ) {
    }

    /**
     * Set stock status and qty
     *
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @throws \Exception
     */
    public function beforeSetStockItem(
        StockRegistryStorage $stockRegistryStorage,
        int $productId,
        int $scopeId,
        StockItemInterface $value
    ): array {
        if ($this->settingsHelper->useStoreOffers()) {
            $offerStock  = $this->offerInventoryHelper->getCurrentOfferStock($productId);
            if ($offerStock !== null && $offerStock->getIsInStock()) {
                $value->setIsInStock($offerStock->getIsInStock());
            }

            if ($offerStock !== null && $offerStock->getQty()) {
                $value->setQty($offerStock->getQty());
            }
        }

        return [$productId, $scopeId, $value];
    }
}

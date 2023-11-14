<?php

/**
 * Plugin StockStateProviderPlugin
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Plugin;

use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Model\StockStateProvider;
use Smile\RetailerOffer\Helper\Settings;

/**
 * StockStateProviderPlugin class on \Magento\CatalogInventory\Model\StockStateProvider
 */
class StockStateProviderPlugin
{
    /**
     * StockItemPlugin constructor.
     */
    public function __construct(
        private Settings $settingsHelper
    ) {
    }

    /**
     * Check quantity
     *
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @throws \Exception
     */
    public function beforeCheckQty(
        StockStateProvider $stockState,
        StockItemInterface $stockItem,
        int|float $qty
    ): array {
        if ($this->settingsHelper->useStoreOffers()) {
            if ($stockItem->getQty() - $qty < 0) {
                $stockItem->setManageStock(false);
            }
        }

        return [$stockItem, $qty];
    }
}

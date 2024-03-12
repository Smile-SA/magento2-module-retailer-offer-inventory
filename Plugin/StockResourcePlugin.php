<?php

/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Plugin;

use Closure;
use Smile\RetailerOfferInventory\Model\ResourceModel\Stock;

/**
 * Plugin on Stock Resource Model.
 * Used to decrement offer inventory instead of Web one.
 */
class StockResourcePlugin
{
    /**
     * StockItemPlugin constructor.
     */
    public function __construct(
        private Stock $offerInventoryResource
    ) {
    }

    /**
     * Compute item qty correction with Offer Inventory Resource if needed.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundCorrectItemsQty(
        \Magento\CatalogInventory\Model\ResourceModel\Stock $stockResource,
        Closure $proceed,
        array $items,
        int $websiteId,
        string $operator
    ): void {
        $this->offerInventoryResource->correctItemsQty($items, $operator);
    }
}

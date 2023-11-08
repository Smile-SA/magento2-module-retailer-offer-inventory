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
use Smile\RetailerOffer\Helper\Settings;
use Smile\RetailerOfferInventory\Model\ResourceModel\Stock;

/**
 * Plugin on Stock Resource Model.
 * Used to decrement offer inventory instead of Web one.
 *
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class StockResourcePlugin
{
    private Stock $offerInventoryResource;

    /**
     * StockItemPlugin constructor.
     *
     * @param \Smile\RetailerOffer\Helper\Settings                    $settingsHelper         Settings Helper
     * @param \Smile\RetailerOfferInventory\Model\ResourceModel\Stock $offerInventoryResource Offer Inventory
     */
    public function __construct(
        private Settings $settingsHelper,
        Stock $offerInventoryResource
    ) {
        $this->offerInventoryResource = $offerInventoryResource;
    }

    /**
     * Compute item qty correction with Offer Inventory Resource if needed.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param \Magento\CatalogInventory\Model\ResourceModel\Stock $stockResource Stock Item Resource Model
     * @param \Closure                                            $proceed       correctItemsQty() method
     * @param array                                               $items         Items being corrected
     * @param int                                                 $websiteId     Website Id
     * @param string                                              $operator      Operator (+ or -)
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

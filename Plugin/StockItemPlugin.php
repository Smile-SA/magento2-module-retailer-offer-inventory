<?php

/**
 * Plugin StockItemPlugin
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Plugin;

use Magento\CatalogInventory\Model\Stock\Item;
use Smile\RetailerOffer\Helper\Settings;
use Smile\RetailerOfferInventory\Helper\OfferInventory;

/**
 * StockItemPlugin class on \Magento\CatalogInventory\Model\Stock\Item
 */
class StockItemPlugin
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
     * Return offer availability (if any) instead of the product one.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) We do not need to call the parent method.
     * @throws \Exception
     */
    public function afterSetIsInStock(Item $item, bool|Item $result): Item
    {
        if ($this->settingsHelper->useStoreOffers()) {
            $offerStock = $this->offerInventoryHelper->getCurrentOfferStock($item->getProductId());
            if ($offerStock !== null && $offerStock->getIsInStock() === 0) {
                return $item->setData(Item::IS_IN_STOCK, $offerStock->getIsInStock());
            }
        }

        return $result;
    }
}

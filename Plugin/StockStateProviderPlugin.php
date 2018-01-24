<?php
/**
 * Plugin StockStateProviderPlugin
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Plugin;

use Magento\CatalogInventory\Model\StockStateProvider;
use Magento\CatalogInventory\Api\Data\StockItemInterface;

/**
 * Class StockStateProviderPlugin
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class StockStateProviderPlugin
{
    /**
     * @var \Smile\RetailerOffer\Helper\Settings
     */
    private $settingsHelper;

    /**
     * StockItemPlugin constructor.
     *
     * @param \Smile\RetailerOffer\Helper\Settings $settingsHelper Settings Helper
     *
     * @internal param \Smile\RetailerOfferInventory\Plugin\OfferStockHelper $offerStockHelper The Offer stock helper
     */
    public function __construct(
        \Smile\RetailerOffer\Helper\Settings $settingsHelper
    ) {
        $this->settingsHelper = $settingsHelper;
    }


    /**
     * Check quantity
     *
     * @param StockStateProvider $stockState Stock state
     * @param StockItemInterface $stockItem  Stock item
     * @param int|float          $qty        Quantity
     *
     * @return array
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @throws \Exception
     */
    public function beforeCheckQty(
        StockStateProvider $stockState,
        StockItemInterface $stockItem,
        $qty
    ) {
        if ($this->settingsHelper->useStoreOffers()) {
            if ($stockItem->getQty() - $qty < 0) {
                $stockItem->setManageStock(false);
            }
        }

        return [$stockItem, $qty];
    }
}

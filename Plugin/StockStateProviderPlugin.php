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
use Smile\RetailerOfferInventory\Helper\OfferStock as OfferStockHelper;

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
     * @var OfferStockHelper
     */
    private $helper;

    /**
     * HelperProductPlugin constructor.
     *
     * @param OfferStockHelper $offerStockHelper The Offer stock helper
     */
    public function __construct(
        OfferStockHelper $offerStockHelper
    ) {
        $this->helper = $offerStockHelper;
    }

    /**
     * Check quantity
     *
     * @param StockStateProvider $stockState
     * @param StockItemInterface $stockItem
     * @param int|float          $qty
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
        if ($this->helper->useStoreOffers()) {
            if ($stockItem->getQty() - $qty < 0) {
                $stockItem->setManageStock(false);
            }
        }

        return [$stockItem, $qty];
    }
}

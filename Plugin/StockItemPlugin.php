<?php
/**
 * Plugin StockItemPlugin
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Plugin;

use Magento\CatalogInventory\Model\Stock\Item as StockItem;
use Smile\RetailerOfferInventory\Helper\OfferStock as OfferStockHelper;

/**
 * Class StockItemPlugin
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class StockItemPlugin
{
    /**
     * @var OfferStockHelper
     */
    private $helper;

    /**
     * ProductPlugin constructor.
     *
     * @param OfferStockHelper $offerStockHelper The Offer stock helper
     */
    public function __construct(
        OfferStockHelper $offerStockHelper
    ) {
        $this->helper = $offerStockHelper;
    }

    /**
     * Set is_in_stock value
     *
     * @param StockItem $stockItem
     * @param bool|int  $result
     * @param bool|int  $isInStock
     *
     * @return bool|int
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @throws \Exception
     */
    public function afterSetIsInStock(
        StockItem $stockItem,
        $result,
        $isInStock
    ) {

        if ($this->helper->useStoreOffers()) {
            $result = true;
            $offerStock  = $this->helper->getCurrentOfferStock($stockItem->getProductId());
            if ($offerStock !== null && $offerStock->getIsInStock()) {
                $result = (int) $offerStock->getIsInStock();
            }
        }

        return $result;
    }


    /**
     * Set qty value
     *
     * @param StockItem $stockItem
     * @param int       $result
     * @param int       $qty
     *
     * @return int
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @throws \Exception
     */
    public function afterSetQty(
        StockItem $stockItem,
        $result,
        $qty
    ) {

        if ($this->helper->useStoreOffers()) {
            $result = true;
            $offerStock  = $this->helper->getCurrentOfferStock($stockItem->getProductId());
            if ($offerStock !== null && $offerStock->getQty()) {
                $result = (int) $offerStock->getQty();
            }
        }

        return $result;
    }
}

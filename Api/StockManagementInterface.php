<?php
/**
 * Interface OfferStockManagement
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\RetailerOfferInventory\Api;

/**
 * Interface StockManagementInterface
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
interface StockManagementInterface
{
    /**
     * Get back to stock (when order is canceled or whatever else)
     *
     * @param \Magento\Sales\Model\Order\Item $stockItem Stock item object.
     * @param float                           $qty       Quantity.
     *
     * @return bool
     */
    public function backItemQty($stockItem, $qty);
}

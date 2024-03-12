<?php

/**
 * Interface OfferStockManagement
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Api;

use Magento\Sales\Model\Order\Item;

/**
 * Interface StockManagementInterface
 */
interface StockManagementInterface
{
    /**
     * Get back to stock (when order is canceled or whatever else)
     *
     * @param Item $stockItem Stock item object.
     * @param float $qty Quantity.
     * @return bool
     */
    public function backItemQty(Item $stockItem, float $qty): bool;
}

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
use Magento\CatalogInventory\Observer\CancelOrderItemObserver;
use Magento\Framework\Event\Observer;
use Smile\RetailerOfferInventory\Api\StockManagementInterface;

/**
 * Plugin on \Magento\CatalogInventory\Observer\CancelOrderItemObserver
 */
class CancelOrderItemObserverPlugin
{
    /**
     * CancelOrderItemObserverPlugin constructor.
     */
    public function __construct(
        protected StockManagementInterface $stockManagement
    ) {
    }

    /**
     * Back item qty to offer inventory if needed.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundExecute(
        CancelOrderItemObserver $subject,
        Closure $proceed,
        Observer $observer
    ): void {
        // Intentional omission of parent $proceed : we do not want to change global inventory.
        /** @var \Magento\Sales\Model\Order\Item $item */
        $item     = $observer->getEvent()->getItem();
        $children = $item->getChildrenItems();
        $qty      = $item->getQtyOrdered() - max($item->getQtyShipped(), $item->getQtyInvoiced()) - $item
                                                                                                     ->getQtyCanceled();
        if ($item->getId() && $item->getProductId() && empty($children) && $qty) {
            $this->stockManagement->backItemQty($item, $qty);
        }
    }
}

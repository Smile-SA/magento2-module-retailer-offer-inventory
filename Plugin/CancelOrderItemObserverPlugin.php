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

/**
 * Plugin on \Magento\CatalogInventory\Observer\CancelOrderItemObserver
 */
class CancelOrderItemObserverPlugin
{
    /**
     * Do not give the qty back to the global inventory.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundExecute(
        CancelOrderItemObserver $subject,
        Closure $proceed,
        Observer $observer
    ): void {
        // Intentional omission of parent $proceed : we do not want to change global inventory.
        // The offer inventory is given back by \Smile\RetailerOfferInventory\Observer\BackItemQtyOnOrderCancel.
    }
}

<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\RetailerOfferInventory\Plugin;

/**
 * Plugin on \Magento\CatalogInventory\Observer\CancelOrderItemObserver
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class CancelOrderItemObserverPlugin
{
    /**
     * @var \Smile\RetailerOffer\Helper\Settings
     */
    private $settingsHelper;

    /**
     * CancelOrderItemObserverPlugin constructor.
     *
     * @param \Smile\RetailerOffer\Helper\Settings                       $settingsHelper           Settings Helper
     * @param \Smile\RetailerOfferInventory\Api\StockManagementInterface $stockManagementInterface Offer inventory management
     */
    public function __construct(
        \Smile\RetailerOffer\Helper\Settings $settingsHelper,
        \Smile\RetailerOfferInventory\Api\StockManagementInterface $stockManagementInterface
    ) {
        $this->settingsHelper  = $settingsHelper;
        $this->stockManagement = $stockManagementInterface;
    }

    /**
     * Back item qty to offer inventory if needed.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param \Magento\CatalogInventory\Observer\CancelOrderItemObserver $subject  The observer
     * @param \Closure                                                   $proceed  The execute Method of the Observer
     * @param \Magento\Framework\Event\Observer                          $observer The event observer
     */
    public function aroundExecute(
        \Magento\CatalogInventory\Observer\CancelOrderItemObserver $subject,
        \Closure $proceed,
        \Magento\Framework\Event\Observer $observer
    ) {
        // Intentional omission of parent $proceed : we do not want to change global inventory.
        /** @var \Magento\Sales\Model\Order\Item $item */
        $item     = $observer->getEvent()->getItem();
        $children = $item->getChildrenItems();
        $qty      = $item->getQtyOrdered() - max($item->getQtyShipped(), $item->getQtyInvoiced()) - $item->getQtyCanceled();
        if ($item->getId() && $item->getProductId() && empty($children) && $qty) {
            $this->stockManagement->backItemQty($item, $qty);
        }
    }
}

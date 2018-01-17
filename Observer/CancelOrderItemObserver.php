<?php
/**
 * Observer OfferStockManagement
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\RetailerOfferInventory\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Smile\RetailerOfferInventory\Api\StockManagementInterface;

/**
 * Class OfferStockManagement
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class CancelOrderItemObserver implements ObserverInterface
{
    /**
     * @var StockManagementInterface
     */
    protected $stockManagement;

    /**
     * @param StockManagementInterface $stockManagement
     */
    public function __construct(
        StockManagementInterface $stockManagement
    ) {
        $this->stockManagement = $stockManagement;
    }


    /**
     * Cancel order item
     *
     * @param   EventObserver $observer
     * @return  void
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Sales\Model\Order\Item $item */
        $item = $observer->getEvent()->getItem();
        $children = $item->getChildrenItems();
        $qty = $item->getQtyOrdered() - max($item->getQtyShipped(), $item->getQtyInvoiced()) - $item->getQtyCanceled();
        if ($item->getId() && $item->getProductId() && empty($children) && $qty) {
            $this->stockManagement->backItemQty($item, $qty);
        }
    }
}

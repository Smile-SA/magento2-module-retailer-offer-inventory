<?php

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Smile\RetailerOfferInventory\Api\StockManagementInterface;

/**
 * Back item qty to offer inventory when an order item is canceled.
 */
class BackItemQtyOnOrderCancel implements ObserverInterface
{
    public function __construct(private StockManagementInterface $stockManagement)
    {
    }

    /**
     * @inheritdoc
     */
    public function execute(Observer $observer): void
    {
        /** @var \Magento\Sales\Model\Order\Item $item */
        $item = $observer->getEvent()->getItem();
        $qty = $item->getQtyOrdered() - max($item->getQtyShipped(), $item->getQtyInvoiced()) - $item->getQtyCanceled();
        if ($item->getId() && $item->getProductId() && empty($item->getChildrenItems()) && $qty) {
            $this->stockManagement->backItemQty($item, $qty);
        }
    }
}

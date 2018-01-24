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
namespace Smile\RetailerOfferInventory\Rewrite\CatalogInventory\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Smile\RetailerOfferInventory\Api\StockManagementInterface;
use Magento\CatalogInventory\Api\StockManagementInterface as ManagementInterface;

/**
 * Class OfferStockManagement
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class CancelOrderItemObserver extends \Magento\CatalogInventory\Observer\CancelOrderItemObserver
{
    /**
     * @var StockManagementInterface
     */
    protected $stockManagement;

    /**
     * CancelOrderItemObserver constructor.
     *
     * @param ManagementInterface                                    $managementInterface ManagementInterface
     * @param \Magento\Catalog\Model\Indexer\Product\Price\Processor $priceIndexer        Price indexer
     * @param StockManagementInterface                               $stockManagement     Stock management
     */
    public function __construct(
        ManagementInterface $managementInterface,
        \Magento\Catalog\Model\Indexer\Product\Price\Processor $priceIndexer,
        StockManagementInterface $stockManagement
    ) {
        parent::__construct($managementInterface, $priceIndexer);
        $this->stockManagement = $stockManagement;
    }


    /**
     * Cancel order item
     *
     * @param   EventObserver $observer Observer
     * @return  void
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Sales\Model\Order\Item $item */
        $item     = $observer->getEvent()->getItem();
        $children = $item->getChildrenItems();
        $qty      = $item->getQtyOrdered() - max($item->getQtyShipped(), $item->getQtyInvoiced()) - $item->getQtyCanceled();
        if ($item->getId() && $item->getProductId() && empty($children) && $qty) {
            $this->stockManagement->backItemQty($item, $qty);
        }
    }
}

<?php
/**
 * Model OfferStockManagement
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\RetailerOfferInventory\Model;

use Smile\RetailerOfferInventory\Api\StockManagementInterface;

/**
 * Class OfferStockManagement
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class StockManagement implements StockManagementInterface
{
    /**
     * @var \Smile\RetailerOffer\Helper\Offer
     */
    private $helper;

    /**
     * @var \Smile\RetailerOfferInventory\Api\StockItemRepositoryInterface
     */
    private $stockItemRepository;

    /**
     * @param \Smile\RetailerOffer\Helper\Offer                              $offerHelper         Offer stock helper
     * @param \Smile\RetailerOfferInventory\Api\StockItemRepositoryInterface $stockItemRepository Model repository
     */
    public function __construct(
        \Smile\RetailerOffer\Helper\Offer $offerHelper,
        \Smile\RetailerOfferInventory\Api\StockItemRepositoryInterface $stockItemRepository
    ) {
        $this->helper              = $offerHelper;
        $this->stockItemRepository = $stockItemRepository;
    }

    /**
     * Get back to stock (when order is canceled or whatever else)
     *
     * @param \Magento\Sales\Model\Order\Item $orderItem Order item
     * @param float                           $qty       Quantity
     *
     * @return bool
     * @throws \Exception
     */
    public function backItemQty($orderItem, $qty)
    {
        $sellerId = $orderItem->getOrder()->getSellerId();
        if ($sellerId) {
            $product = $orderItem->getProduct();
            $offer   = $this->helper->getOffer($product, $sellerId);

            if ($offer->getExtensionAttributes()->getStockItem() !== null) {
                $stockItem = $offer->getExtensionAttributes()->getStockItem();
                $stockItem->setQty($stockItem->getQty() + $qty);

                if ($stockItem->getQty() > 0) {
                    $stockItem->setIsInStock(true);
                }
                $this->stockItemRepository->save($stockItem);
            }
        }

        return true;
    }
}

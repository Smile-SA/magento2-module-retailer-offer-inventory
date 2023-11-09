<?php

/**
 * Model OfferStockManagement
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Model;

use Magento\Sales\Model\Order\Item;
use Smile\RetailerOffer\Helper\Offer;
use Smile\RetailerOfferInventory\Api\StockItemRepositoryInterface;
use Smile\RetailerOfferInventory\Api\StockManagementInterface;

/**
 * Class OfferStockManagement
 *
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class StockManagement implements StockManagementInterface
{
    private Offer $helper;

    /**
     * @param \Smile\RetailerOffer\Helper\Offer                              $offerHelper         Offer stock helper
     * @param \Smile\RetailerOfferInventory\Api\StockItemRepositoryInterface $stockItemRepository Model repository
     */
    public function __construct(
        Offer $offerHelper,
        private StockItemRepositoryInterface $stockItemRepository
    ) {
        $this->helper              = $offerHelper;
    }

    /**
     * Get back to stock (when order is canceled or whatever else)
     *
     * @param \Magento\Sales\Model\Order\Item $orderItem Order item
     * @param float                           $qty       Quantity
     * @throws \Exception
     */
    public function backItemQty(Item $orderItem, float $qty): bool
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

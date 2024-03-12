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
 */
class StockManagement implements StockManagementInterface
{
    public function __construct(
        private Offer $offerHelper,
        private StockItemRepositoryInterface $stockItemRepository
    ) {
    }

    /**
     * Get back to stock (when order is canceled or whatever else)
     *
     * @throws \Exception
     */
    public function backItemQty(Item $orderItem, float $qty): bool
    {
        $sellerId = $orderItem->getOrder()->getSellerId();
        if ($sellerId) {
            $product = $orderItem->getProduct();
            $offer   = $this->offerHelper->getOffer($product, $sellerId);

            if ($offer->getExtensionAttributes()->getStockItem() !== null) {
                $stockItem = $offer->getExtensionAttributes()->getStockItem();
                $stockItem->setQty((int) ($stockItem->getQty() + $qty));

                if ($stockItem->getQty() > 0) {
                    $stockItem->setIsInStock(1);
                }
                $this->stockItemRepository->save($stockItem);
            }
        }

        return true;
    }
}

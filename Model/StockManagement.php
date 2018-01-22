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

use Smile\RetailerOfferInventory\Helper\OfferStock as OfferStockHelper;
use Smile\RetailerOfferInventory\Api\StockManagementInterface;
use Smile\RetailerOfferInventory\Api\StockItemRepositoryInterface;

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
     * @var OfferStockHelper
     */
    private $helper;

    /**
     * @var StockItemRepositoryInterface
     */
    private $modelRepository;

    /**
     * @param OfferStockHelper             $offerStockHelper Offer stock helper
     * @param StockItemRepositoryInterface $modelRepository  Model repository
     */
    public function __construct(
        OfferStockHelper $offerStockHelper,
        StockItemRepositoryInterface $modelRepository
    ) {
        $this->helper          = $offerStockHelper;
        $this->modelRepository = $modelRepository;
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
        $productId = $orderItem->getProductId();
        $offerStock = $this->helper->getOfferStock($productId, $sellerId);

        if ($offerStock !== null) {
            $offerStock->setQty($offerStock->getQty() + $qty);

            if ($offerStock->getQty() > 0) {
                $offerStock->setIsInStock(true);
            }
            $this->modelRepository->save($offerStock);
        }

        return true;
    }
}

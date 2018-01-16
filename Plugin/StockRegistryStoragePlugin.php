<?php
/**
 * Plugin StockRegistryStoragePlugin
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Plugin;

use Smile\RetailerOfferInventory\Helper\OfferStock as OfferStockHelper;
use Magento\CatalogInventory\Model\StockRegistryStorage;
use Magento\CatalogInventory\Api\Data\StockItemInterface;

/**
 * Class StockRegistryStoragePlugin
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class StockRegistryStoragePlugin
{
    /**
     * @var OfferStockHelper
     */
    private $helper;

    /**
     * ProductPlugin constructor.
     *
     * @param OfferStockHelper $offerStockHelper The Offer stock helper
     */
    public function __construct(
        OfferStockHelper $offerStockHelper
    ) {
        $this->helper = $offerStockHelper;
    }

    /**
     * Set stock status and qty
     *
     * @param StockRegistryStorage $stockRegistryStorage
     * @param int                  $productId
     * @param int                  $scopeId
     * @param StockItemInterface   $value
     *
     * @return array
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @throws \Exception
     */
    public function beforeSetStockItem(
        StockRegistryStorage $stockRegistryStorage,
        $productId,
        $scopeId,
        StockItemInterface $value
    ) {
        if ($this->helper->useStoreOffers()) {
            $offerStock  = $this->helper->getCurrentOfferStock($productId);
            if ($offerStock !== null && $offerStock->getIsInStock()) {
                $value->setIsInStock($offerStock->getIsInStock());
            }

            if ($offerStock !== null && $offerStock->getQty()) {
                $value->setQty($offerStock->getQty());
            }
        }

        return [$productId,
            $scopeId,
            $value
        ];
    }
}

<?php

/**
 * Plugin StockRegistryStoragePlugin
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Plugin;

use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Model\StockRegistryStorage;
use Smile\RetailerOffer\Helper\Settings;
use Smile\RetailerOfferInventory\Helper\OfferInventory;
use Smile\RetailerOfferInventory\Helper\OfferStock as OfferStockHelper;

/**
 * StockRegistryStoragePlugin class on \Magento\CatalogInventory\Model\StockRegistryStorage
 *
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class StockRegistryStoragePlugin
{
    private OfferInventory $helper;

    /**
     * StockItemPlugin constructor.
     *
     * @param \Smile\RetailerOffer\Helper\Settings                $settingsHelper       Settings Helper
     * @param \Smile\RetailerOfferInventory\Helper\OfferInventory $offerInventoryHelper Offer Inventory Helper
     * @internal param \Smile\RetailerOfferInventory\Plugin\OfferStockHelper $offerStockHelper The Offer stock helper
     */
    public function __construct(
        private Settings $settingsHelper,
        OfferInventory $offerInventoryHelper
    ) {
        $this->helper         = $offerInventoryHelper;
    }

    /**
     * Set stock status and qty
     *
     * @param StockRegistryStorage $stockRegistryStorage Stock Registry Storage
     * @param int                  $productId            Product id
     * @param int                  $scopeId              Scope id
     * @param StockItemInterface   $value                Value
     * @return array
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @throws \Exception
     */
    public function beforeSetStockItem(
        StockRegistryStorage $stockRegistryStorage,
        int $productId,
        int $scopeId,
        StockItemInterface $value
    ): array {
        if ($this->settingsHelper->useStoreOffers()) {
            $offerStock  = $this->helper->getCurrentOfferStock($productId);
            if ($offerStock !== null && $offerStock->getIsInStock()) {
                $value->setIsInStock($offerStock->getIsInStock());
            }

            if ($offerStock !== null && $offerStock->getQty()) {
                $value->setQty($offerStock->getQty());
            }
        }

        return [$productId, $scopeId, $value];
    }
}

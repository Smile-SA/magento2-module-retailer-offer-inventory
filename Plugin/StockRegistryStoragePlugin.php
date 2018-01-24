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
     * @var \Smile\RetailerOffer\Helper\Settings
     */
    private $settingsHelper;

    /**
     * @var \Smile\RetailerOfferInventory\Helper\OfferInventory
     */
    private $helper;

    /**
     * StockItemPlugin constructor.
     *
     * @param \Smile\RetailerOffer\Helper\Settings                $settingsHelper       Settings Helper
     * @param \Smile\RetailerOfferInventory\Helper\OfferInventory $offerInventoryHelper Offer Inventory Helper
     *
     * @internal param \Smile\RetailerOfferInventory\Plugin\OfferStockHelper $offerStockHelper The Offer stock helper
     */
    public function __construct(
        \Smile\RetailerOffer\Helper\Settings $settingsHelper,
        \Smile\RetailerOfferInventory\Helper\OfferInventory $offerInventoryHelper
    ) {
        $this->settingsHelper = $settingsHelper;
        $this->helper         = $offerInventoryHelper;
    }

    /**
     * Set stock status and qty
     *
     * @param StockRegistryStorage $stockRegistryStorage Stock Registry Storage
     * @param int                  $productId            Product id
     * @param int                  $scopeId              Scope id
     * @param StockItemInterface   $value                Value
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

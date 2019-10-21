<?php
/**
 * Plugin StockItemPlugin
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Plugin;

/**
 * Class StockItemPlugin
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class StockItemPlugin
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
     * Return offer availability (if any) instead of the product one.
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) We do not need to call the parent method.
     *
     * @param \Magento\CatalogInventory\Model\Stock\Item $item   The item
     * @param boolean                                    $result Result
     *
     * @return \Magento\CatalogInventory\Model\Stock\Item
     * @throws \Exception
     */
    public function afterSetIsInStock(\Magento\CatalogInventory\Model\Stock\Item $item, $result)
    {
        if ($this->settingsHelper->useStoreOffers()) {
            $offerStock = $this->helper->getCurrentOfferStock($item->getProductId());
            if ($offerStock !== null && $offerStock->getIsInStock() === 0) {
                return $item->setData(\Magento\CatalogInventory\Model\Stock\Item::IS_IN_STOCK, $offerStock->getIsInStock());
            }
        }

        return $result;
    }
}

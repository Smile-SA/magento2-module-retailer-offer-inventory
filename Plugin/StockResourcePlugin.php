<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\RetailerOfferInventory\Plugin;

/**
 * Plugin on Stock Resource Model.
 * Used to decrement offer inventory instead of Web one.
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class StockResourcePlugin
{
    /**
     * @var \Smile\RetailerOffer\Helper\Settings
     */
    private $settingsHelper;

    /**
     * @var \Smile\RetailerOfferInventory\Helper\OfferInventory
     */
    private $offerInventoryResource;

    /**
     * StockItemPlugin constructor.
     *
     * @param \Smile\RetailerOffer\Helper\Settings                    $settingsHelper         Settings Helper
     * @param \Smile\RetailerOfferInventory\Model\ResourceModel\Stock $offerInventoryResource Offer Inventory
     *
     */
    public function __construct(
        \Smile\RetailerOffer\Helper\Settings $settingsHelper,
        \Smile\RetailerOfferInventory\Model\ResourceModel\Stock $offerInventoryResource
    ) {
        $this->settingsHelper         = $settingsHelper;
        $this->offerInventoryResource = $offerInventoryResource;
    }

    /**
     * Compute item qty correction with Offer Inventory Resource if needed.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param \Magento\CatalogInventory\Model\ResourceModel\Stock $stockResource Stock Item Resource Model
     * @param \Closure                                            $proceed       correctItemsQty() method
     * @param array                                               $items         Items being corrected
     * @param int                                                 $websiteId     Website Id
     * @param string                                              $operator      Operator (+ or -)
     */
    public function aroundCorrectItemsQty(
        \Magento\CatalogInventory\Model\ResourceModel\Stock $stockResource,
        \Closure $proceed,
        array $items,
        $websiteId,
        $operator
    ) {
        if (!$this->settingsHelper->useStoreOffers()) {
            $proceed($items, $websiteId, $operator);

            return;
        }

        $this->offerInventoryResource->correctItemsQty($items, $operator);
    }
}

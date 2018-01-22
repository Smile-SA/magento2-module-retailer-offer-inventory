<?php
/**
 * Plugin ProductPlugin
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Plugin;

use Magento\Catalog\Model\Product;
use Smile\StoreLocator\CustomerData\CurrentStore;
use Smile\RetailerOfferInventory\Helper\OfferStock as OfferStockHelper;
use Smile\RetailerOffer\Helper\Offer as OfferHelper;
use Smile\RetailerOffer\Plugin\ProductPlugin as RetailerOfferProductPlugin;
use Smile\RetailerOffer\Helper\Settings;
use Magento\Framework\App\State;

/**
 * Class ProductPlugin
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class ProductPlugin extends RetailerOfferProductPlugin
{
    /**
     * @var OfferStockHelper
     */
    private $helper;

    /**
     * ProductPlugin constructor.
     *
     * @param OfferStockHelper $offerStockHelper The Offer stock helper
     * @param OfferHelper      $offerHelper      The Offer helper
     * @param CurrentStore     $currentStore     The Retailer Data Object
     * @param State            $state            The state
     * @param Settings         $settingsHelper   The settings helper
     */
    public function __construct(
        OfferStockHelper $offerStockHelper,
        OfferHelper $offerHelper,
        CurrentStore $currentStore,
        State $state,
        Settings $settingsHelper
    ) {
        parent::__construct($offerHelper, $currentStore, $state, $settingsHelper);

        $this->helper = $offerStockHelper;
    }

    /**
     * Return offer availability (if any) instead of the product one.
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) We do not need to call the parent method.
     *
     * @param \Magento\Catalog\Model\Product $product The product
     * @param bool                           $result  Result
     *
     * @return bool
     * @throws \Exception
     */
    public function afterIsAvailable(Product $product, $result)
    {
        $isAvailable = $result;

        if ($isAvailable) {
            $offerStock  = $this->helper->getCurrentOfferStock($product->getId());
            if ($offerStock !== null && $offerStock->getIsInStock() === 0) {
                $isAvailable = (bool) $offerStock->getIsInStock();
            }
        }

        return $isAvailable;
    }
}

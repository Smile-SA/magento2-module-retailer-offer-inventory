<?php
/**
 * Plugin HelperProductPlugin
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Plugin;

use Magento\Catalog\Helper\Product as ProductHelper;
use Smile\RetailerOfferInventory\Helper\OfferStock as OfferStockHelper;

/**
 * Class HelperProductPlugin
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class HelperProductPlugin
{
    /**
     * @var OfferStockHelper
     */
    private $helper;

    /**
     * HelperProductPlugin constructor.
     *
     * @param OfferStockHelper $offerStockHelper The Offer stock helper
     */
    public function __construct(
        OfferStockHelper $offerStockHelper
    ) {
        $this->helper = $offerStockHelper;
    }

    /**
     * Disable product if !available in offer
     *
     * @param ProductHelper                        $productHelper Product helper
     * @param int                                  $productId     Product id
     * @param \Magento\Framework\App\Action\Action $controller    Controller
     * @param \Magento\Framework\DataObject        $params        Params
     *
     * @return array
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @throws \Exception
     */
    public function beforeInitProduct(
        ProductHelper $productHelper,
        $productId,
        $controller,
        $params = null
    ) {
        /*if ($this->helper->useStoreOffers()) {
            $offer      = $this->helper->getCurrentOffer($productId);
            $offerStock = $this->helper->getCurrentOfferStock($productId);

            if ($offerStock !== null
                && $offerStock->getIsInStock() === 0
                && $offer !== null
                && !$offer->isAvailable()
            ) {
                $productId = false;
            }
        }*/

        return [$productId,
            $controller,
            $params,
        ];
    }
}

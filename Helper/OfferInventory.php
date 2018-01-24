<?php
/**
 * Helper
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\RetailerOfferInventory\Helper;

use Smile\Offer\Api\Data\OfferInterface;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Generic Helper for Retailer OfferInventory
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class OfferInventory extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Smile\RetailerOffer\Helper\Settings
     */
    private $settingsHelper;

    /**
     * @var \Smile\RetailerOffer\Helper\Offer
     */
    private $offerHelper;

    /**
     * @var \Magento\Catalog\Api\Data\ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * ProductPlugin constructor.
     *
     * @param \Magento\Framework\App\Helper\Context             $context        Helper context.
     * @param \Smile\RetailerOffer\Helper\Settings              $settingsHelper Settings Helper
     * @param \Smile\RetailerOffer\Helper\Offer                 $offerHelper    Offer Helper
     * @param \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory Product Factory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Smile\RetailerOffer\Helper\Settings $settingsHelper,
        \Smile\RetailerOffer\Helper\Offer $offerHelper,
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory
    ) {
        $this->settingsHelper = $settingsHelper;
        $this->offerHelper    = $offerHelper;
        $this->productFactory = $productFactory;

        parent::__construct($context);
    }

    /**
     * Retrieve Offer for the product by retailer id and pickup date.
     *
     * @param integer $productId  The product
     * @param integer $retailerId The retailer Id
     *
     * @return OfferInterface
     */
    public function getOffer($productId, $retailerId)
    {
        $product = $this->productFactory->create()->setId($productId);

        return $this->offerHelper->getOffer($product, $retailerId);
    }

    /**
     * Retrieve Offer for the product by retailer id.
     *
     * @param integer $productId  The product
     * @param integer $retailerId The retailer Id
     *
     * @return StockItemInterface
     * @throws \Exception
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getOfferStock($productId, $retailerId)
    {
        $offerStock = null;

        $offer = $this->getOffer($productId, $retailerId);
        if ($offer->getId() && $offer->getExtensionAttributes()->getStockItem()) {
            $offerStock = $offer->getExtensionAttributes()->getStockItem();
        }

        return $offerStock;
    }

    /**
     * Retrieve Current Offer stock for the product.
     *
     * @param int $productId Product id.
     *
     * @return StockItemInterface
     * @throws \Exception
     */
    public function getCurrentOfferStock($productId)
    {
        $product = $this->productFactory->create()->setId($productId);
        $offer   = $this->offerHelper->getCurrentOffer($product);

        if ($offer->getProductId() && $offer->getSellerId()) {
            return $this->getOfferStock($offer->getProductId(), $offer->getSellerId());
        }

        return null;
    }

    /**
     * Retrieve Current Offer for the product.
     *
     * @param int $productId Product id.
     *
     * @return OfferInterface
     * @throws \Exception
     */
    public function getCurrentOffer($productId)
    {
        $product = $this->productFactory->create()->setId($productId);
        $offer   = $this->offerHelper->getCurrentOffer($product);

        return $offer;
    }
}

<?php

/**
 * Helper
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Helper;

use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Smile\Offer\Api\Data\OfferInterface;
use Smile\RetailerOffer\Helper\Offer;
use Smile\RetailerOffer\Helper\Settings;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Generic Helper for Retailer OfferInventory
 *
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class OfferInventory extends AbstractHelper
{
    /**
     * ProductPlugin constructor.
     *
     * @param \Magento\Framework\App\Helper\Context             $context        Helper context.
     * @param \Smile\RetailerOffer\Helper\Settings              $settingsHelper Settings Helper
     * @param \Smile\RetailerOffer\Helper\Offer                 $offerHelper    Offer Helper
     * @param \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory Product Factory
     */
    public function __construct(
        Context $context,
        private Settings $settingsHelper,
        private Offer $offerHelper,
        private ProductInterfaceFactory $productFactory
    ) {

        parent::__construct($context);
    }

    /**
     * Retrieve Offer for the product by retailer id and pickup date.
     *
     * @param int $productId  The product
     * @param int $retailerId The retailer Id
     */
    public function getOffer(int $productId, int $retailerId): OfferInterface
    {
        $product = $this->productFactory->create()->setId($productId);

        return $this->offerHelper->getOffer($product, $retailerId);
    }

    /**
     * Retrieve Offer for the product by retailer id.
     *
     * @param int $productId  The product
     * @param int $retailerId The retailer Id
     * @throws \Exception
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getOfferStock(int $productId, int $retailerId): StockItemInterface
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
     * @throws \Exception
     */
    public function getCurrentOfferStock(int $productId): ?StockItemInterface
    {
        $product = $this->productFactory->create()->setId($productId);
        $offer   = $this->offerHelper->getCurrentOffer($product);

        if ($offer && $offer->getProductId() && $offer->getSellerId()) {
            return $this->getOfferStock($offer->getProductId(), $offer->getSellerId());
        }

        return null;
    }

    /**
     * Retrieve Current Offer for the product.
     *
     * @param int $productId Product id.
     * @throws \Exception
     */
    public function getCurrentOffer(int $productId): OfferInterface
    {
        $product = $this->productFactory->create()->setId($productId);
        $offer   = $this->offerHelper->getCurrentOffer($product);

        return $offer;
    }
}

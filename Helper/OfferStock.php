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

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Smile\Offer\Api\Data\OfferInterface;
use Smile\Offer\Api\OfferManagementInterface;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;
use Smile\RetailerOfferInventory\Api\StockItemRepositoryInterface as StockItemRepository;
use Magento\Framework\App\State;
use Smile\RetailerOffer\Helper\Settings;
use Smile\StoreLocator\CustomerData\CurrentStore;

/**
 * Generic Helper for Retailer OfferInventory
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class OfferStock extends AbstractHelper
{
    /**
     * @var OfferManagementInterface
     */
    private $offerManagement;

    /**
     * @var OfferInterface[]
     */
    private $offersCache = [];

    /**
     * @var StockItemInterface[]
     */
    private $offersStockCache = [];

    /**
     * @var StockItemRepository
     */
    private $stockItemRepository;

    /**
     * @var \Smile\RetailerOffer\Helper\Settings
     */
    private $settingsHelper;
    /**
     * @var State
     */
    private $state;

    /**
     * @var CurrentStore
     */
    private $currentStore;

    /**
     * ProductPlugin constructor.
     * @param Context                  $context             Helper context.
     * @param OfferManagementInterface $offerManagement     The offer Management
     * @param StockItemRepository      $stockItemRepository The stock item
     * @param State                    $state               The Application State
     * @param Settings                 $settingsHelper      Settings Helper
     * @param CurrentStore             $currentStore        The Retailer Data Object
     *
     */
    public function __construct(
        Context $context,
        OfferManagementInterface $offerManagement,
        StockItemRepository $stockItemRepository,
        State $state,
        Settings $settingsHelper,
        CurrentStore $currentStore
    ) {
        $this->offerManagement = $offerManagement;
        $this->stockItemRepository = $stockItemRepository;
        $this->state          = $state;
        $this->settingsHelper = $settingsHelper;
        $this->currentStore   = $currentStore;

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
        $offer = null;

        if ($productId && $retailerId) {
            $cacheKey = implode('_', [$productId, $retailerId]);

            if (false === isset($this->offersCache[$cacheKey])) {
                $offer = $this->offerManagement->getOffer($productId, $retailerId);
                $this->offersCache[$cacheKey] = $offer;
            }

            $offer = $this->offersCache[$cacheKey];
        }

        return $offer;
    }

    /**
     * Retrieve Offer for the product by retailer id and pickup date.
     *
     * @param integer $productId  The product
     * @param integer $retailerId The retailer Id
     *
     * @return StockItemInterface
     * @throws \Exception
     */

    public function getOfferStock($productId, $retailerId)
    {
        $offerStock = null;

        $offer = $this->getOffer($productId, $retailerId);
        if ($offer->getId()) {
            $cacheKey = implode('_', [$offer->getId(), $retailerId]);

            if (false === isset($this->offersStockCache[$cacheKey])) {
                $offerStock = $this->stockItemRepository->getByOfferId($offer->getId());
                $this->offersStockCache[$cacheKey] = $offerStock;
            }

            $offerStock = $this->offersStockCache[$cacheKey];
        }

        return $offerStock;
    }


    /**
     * Check if we should use store offers
     *
     * @return bool
     * @throws \Exception
     */
    public function useStoreOffers()
    {
        return !($this->isAdmin() || !$this->settingsHelper->isDriveMode());
    }

    /**
     * Check if we are browsing admin area
     *
     * @return bool
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isAdmin()
    {
        return $this->state->getAreaCode() == \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE;
    }

    /**
     * Retrieve Current Offer stock for the product.
     *
     * @param int $productId
     *
     * @return StockItemInterface
     * @throws \Exception
     */
    public function getCurrentOfferStock($productId)
    {
        $offerStock = null;
        $retailerId = null;
        if ($this->currentStore->getRetailer() && $this->currentStore->getRetailer()->getId()) {
            $retailerId = $this->currentStore->getRetailer()->getId();
        }

        if ($retailerId) {
            $offerStock = $this->getOfferStock(
                $productId,
                $retailerId
            );
        }

        return $offerStock;
    }

    /**
     * Retrieve Current Offer for the product.
     *
     * @param int $productId
     *
     * @return OfferInterface
     * @throws \Exception
     */
    public function getCurrentOffer($productId)
    {
        $offer = null;
        $retailerId = null;
        if ($this->currentStore->getRetailer() && $this->currentStore->getRetailer()->getId()) {
            $retailerId = $this->currentStore->getRetailer()->getId();
        }

        if ($retailerId) {
            $offer = $this->getOffer(
                $productId,
                $retailerId
            );
        }

        return $offer;
    }
}

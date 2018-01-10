<?php
/**
 * Plugin AvailabilityPlugin
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Plugin;

use Smile\RetailerOffer\Block\Catalog\Product\Retailer\Availability;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;
use Smile\RetailerOfferInventory\Api\StockItemRepositoryInterface as StockItemRepository;

/**
 * Class AvailabilityPlugin
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class AvailabilityPlugin
{
    /**
     * @var StockItemRepository
     */
    private $stockItemRepository;

    /**
     * AvailabilityPlugin constructor.
     *
     * @param StockItemRepository      $stockItemRepository The stock item
     *
     */
    public function __construct(
        StockItemRepository $stockItemRepository
    ) {
        $this->stockItemRepository = $stockItemRepository;
    }

    /**
     * Set Available by offer stock status
     *
     * @param Availability $availability
     * @param \Closure     $proceed
     *
     * @return array
     * @throws \Exception
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetJsLayout(Availability $availability, \Closure $proceed)
    {
        $storeOffersWithStock = [];

        $jsLayout = $proceed();
        $jsLayout = json_decode($jsLayout, true);

        $storeOffers = $jsLayout['components']['catalog-product-retailer-availability']['storeOffers'];
        foreach ($storeOffers as $offer) {
            if ($offer['isAvailable'] === true) {
                $offerStock  = $this->stockItemRepository->getByOfferId($offer[StockItemInterface::FIELD_OFFER_ID]);
                if ($offerStock !== null && $offerStock->getIsInStock() === 0) {
                    $offer['isAvailable'] = (bool) $offerStock->getIsInStock();
                }
                $storeOffersWithStock[] = $offer;
            }
        }

        $jsLayout['components']['catalog-product-retailer-availability']['storeOffers'] = $storeOffersWithStock;

        return json_encode($jsLayout);
    }
}

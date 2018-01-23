<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\RetailerOfferInventory\Plugin;

/**
 * Offer Plugin
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class OfferPlugin
{
    /**
     * Set the offer as unavailable if it is out of stock.
     *
     * @param \Smile\Offer\Api\Data\OfferInterface $offer  The offer
     * @param boolean                              $result Result of the isAvailable() method of the offer
     *
     * @return boolean
     */
    public function afterIsAvailable(\Smile\Offer\Api\Data\OfferInterface $offer, $result)
    {
        if (true === $result && $offer->getExtensionAttributes() && $offer->getExtensionAttributes()->getStockItem()) {
            $result = (bool) $offer->getExtensionAttributes()->getStockItem()->getIsInStock();
        }

        return $result;
    }
}

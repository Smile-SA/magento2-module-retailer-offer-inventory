<?php

/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Plugin;

use Smile\Offer\Api\Data\OfferInterface;

/**
 * Offer Plugin class on \Smile\Offer\Api\Data\OfferInterface
 */
class OfferPlugin
{
    /**
     * Set the offer as unavailable if it is out of stock.
     */
    public function afterIsAvailable(OfferInterface $offer, bool $result): bool
    {
        if (true === $result && $offer->getExtensionAttributes() && $offer->getExtensionAttributes()->getStockItem()) {
            $result = (bool) $offer->getExtensionAttributes()->getStockItem()->getIsInStock();
        }

        return $result;
    }
}

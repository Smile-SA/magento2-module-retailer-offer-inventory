<?php

/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Plugin;

use Closure;
use Smile\Offer\Api\Data\OfferInterface;
use Smile\RetailerOffer\Ui\Component\Offer\Form\DataProvider;

/**
 * Offer form data provider plugin.
 *
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class OfferEditFormPlugin
{
    /**
     * Append inventory to the Data provider data.
     *
     * @param \Smile\RetailerOffer\Ui\Component\Offer\Form\DataProvider $dataProvider DataProvider.
     * @param \Closure                                                  $proceed      Original method.
     * @return array
     */
    public function aroundGetData(DataProvider $dataProvider, Closure $proceed): array
    {
        $data  = $proceed();
        $offer = $this->getOffer($dataProvider);

        if ($offer !== null && $offer->getExtensionAttributes()->getStockItem()) {
            $stockItem = $offer->getExtensionAttributes()->getStockItem();
            $data[$offer->getId()]['inventory'] = $stockItem->getData();
        }

        return $data;
    }

    /**
     * Return the currently edited offer.
     *
     * @param \Smile\RetailerOffer\Ui\Component\Offer\Form\DataProvider $dataProvider DataProvider.
     * @return \Smile\Offer\Api\Data\OfferInterface|NULL
     */
    private function getOffer(DataProvider $dataProvider): ?OfferInterface
    {
        $offer = $dataProvider->getCollection()->getFirstItem();

        return $offer instanceof OfferInterface ? $offer : null;
    }
}

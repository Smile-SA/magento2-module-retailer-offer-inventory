<?php

/**
 * ResourceModel Product Indexer Fulltext
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Model\ResourceModel\Product\Indexer\Fulltext\Datasource;

use Smile\Offer\Model\ResourceModel\Product\Indexer\Fulltext\Datasource\OfferData;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Class StockData
 *
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class OfferInventoryData extends OfferData
{
    /**
     * @inheritdoc
     */
    public function loadOfferData($productIds): array
    {
        $select = $this->getConnection()->select()
            ->from(['o' => $this->getTable('smile_offer')])
            ->joinLeft(
                ['osi' => $this->getTable(StockItemInterface::TABLE_NAME)],
                'o.offer_id = osi.offer_id',
                ['is_in_stock', 'qty']
            )
            ->where('o.product_id IN(?)', $productIds);

        $offerData = $this->getConnection()->fetchAll($select);
        array_walk($offerData, function (&$offer) {
            $offer['is_in_stock'] = (bool) $offer['is_in_stock'];

            return $offer;
        });

        return $offerData;
    }
}

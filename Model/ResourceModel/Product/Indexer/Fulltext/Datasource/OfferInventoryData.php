<?php
/**
 * ResourceModel Product Indexer Fulltext
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Model\ResourceModel\Product\Indexer\Fulltext\Datasource;

use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Class StockData
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class OfferInventoryData extends \Smile\Offer\Model\ResourceModel\Product\Indexer\Fulltext\Datasource\OfferData
{
    /**
     * {@inheritdoc}
     */
    public function loadOfferData($productIds)
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

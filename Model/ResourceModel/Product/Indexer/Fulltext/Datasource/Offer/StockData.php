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

namespace Smile\RetailerOfferInventory\Model\ResourceModel\Product\Indexer\Fulltext\Datasource\Offer;

use Smile\ElasticsuiteCatalog\Model\ResourceModel\Eav\Indexer\Indexer;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Class StockData
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class StockData extends Indexer
{
    /**
     * Load stock data for a list of offer ids and a given store.
     *
     * @param array $offerIds Offer ids list.
     *
     * @return string
     */
    public function loadOfferStockData($offerIds)
    {
        $select = $this->getConnection()->select()
            ->from(
                ['s' => $this->getTable(StockItemInterface::TABLE_NAME)],
                ['s.'.StockItemInterface::FIELD_IS_IN_STOCK]
            )
            ->where('s.'.StockItemInterface::FIELD_OFFER_ID.' = ?', $offerIds);

        return $this->getConnection()->fetchOne($select);
    }
}

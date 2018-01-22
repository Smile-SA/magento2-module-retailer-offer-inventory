<?php
/**
 * Rewrite OfferData
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Rewrite\Model\Offer\Product\Indexer\Fulltext\Datasource;

use Smile\RetailerOfferInventory\Model\ResourceModel\Product\Indexer\Fulltext\Datasource\Offer\StockData
    as StockDataResourceModel;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;
use Smile\Offer\Model\ResourceModel\Product\Indexer\Fulltext\Datasource\OfferData as OfferDataResourceModel;

/**
 * Class OfferData
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class OfferData extends \Smile\Offer\Model\Product\Indexer\Fulltext\Datasource\OfferData
{
    /**
     * @var StockDataResourceModel
     */
    private $stockDataResourceModel;

    /**
     * Constructor.
     *
     * @param OfferDataResourceModel $offerDataResourceModel Resource model offer data.
     * @param StockDataResourceModel $stockDataResourceModel Resource model.
     */
    public function __construct(
        OfferDataResourceModel $offerDataResourceModel,
        StockDataResourceModel $stockDataResourceModel
    ) {
        parent::__construct($offerDataResourceModel);
        $this->stockDataResourceModel = $stockDataResourceModel;
    }

    /**
     * Add stock status in product index after add offer to index data.
     *
     * {@inheritdoc}
     */
    public function addData($storeId, array $indexData)
    {
        $indexData = parent::addData($storeId, $indexData);
        foreach ($indexData as $productId => $dataRow) {
            if (isset($dataRow['offer']) && $dataRow['offer'] !== null) {
                foreach ($dataRow['offer'] as $key => $offerDataRow) {
                    $offerStockData = $this->stockDataResourceModel
                        ->loadOfferStockData($offerDataRow[StockItemInterface::FIELD_OFFER_ID]);
                    $indexData[$productId]['offer'][$key][StockItemInterface::FIELD_IS_IN_STOCK] = $offerStockData;
                }
            }
        }

        return $indexData;
    }
}

<?php
/**
 * Plugin
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Plugin\Model\Product\Indexer\Fulltext\Datasource;

use Smile\RetailerOfferInventory\Model\ResourceModel\Product\Indexer\Fulltext\Datasource\Offer\StockData
    as ResourceModel;
use Smile\Offer\Model\Product\Indexer\Fulltext\Datasource\OfferData as OfferDataOriginalClass;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Class OfferData
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class OfferData
{
    /**
     * @var ResourceModel
     */
    private $resourceModel;

    /**
     * Constructor.
     *
     * @param ResourceModel $resourceModel Resource model
     */
    public function __construct(ResourceModel $resourceModel)
    {
        $this->resourceModel = $resourceModel;
    }

    /**
     * Add stock status in product index after add offer to index data.
     *
     * @param OfferDataOriginalClass $offerData
     * @param array                  $result
     * @param integer                $storeId
     * @param array                  $indexData
     *
     * @return array
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     */
    public function afterAddData(
        OfferDataOriginalClass $offerData,
        $result,
        $storeId,
        array $indexData
    ) {
        foreach ($result as $productId => $dataRow) {
            foreach ($dataRow['offer'] as $key => $offerDataRow) {
                $offerStockData = $this->resourceModel
                    ->loadOfferStockData($offerDataRow[StockItemInterface::FIELD_OFFER_ID]);
                $result[$productId]['offer'][$key][StockItemInterface::FIELD_IS_IN_STOCK] = $offerStockData;
            }
        }

        return $result;
    }
}

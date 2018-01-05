<?php
/**
 * Model Stock Item Handler
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Model\Stock\Item;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item as StockItemResource;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Class DataSaveHandler
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class DataSaveHandler implements ExtensionInterface
{
    /**
     * @var StockItemResource
     */
    private $stockItemResource;

    public function __construct(StockItemResource $stockItemResource)
    {
        $this->stockItemResource = $stockItemResource;
    }

    /**
     * Perform action on relation/extension attribute
     *
     * @param StockItemInterface|object $entity
     * @param array  $arguments
     *
     * @return object|bool
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     * @throws \Exception
     */
    public function execute($entity, $arguments = [])
    {

        $offerData = $entity->getExtensionAttributes()['offer_stock'] ?? [];
        if (!array_key_exists($this->stockItemResource->getIdFieldName(), $offerData)) {
            $offerData[$this->stockItemResource->getIdFieldName()] = null;
        }
        $offerData['offer_id'] = $entity->getId();
        $offerData = array_filter(
            $offerData,
            function ($dataKey) {
                return in_array(
                    $dataKey,
                    [
                        StockItemInterface::FIELD_OFFER_ID,
                        StockItemInterface::FIELD_QTY,
                        StockItemInterface::FIELD_IS_IN_STOCK
                    ]
                );
            },
            ARRAY_FILTER_USE_KEY
        );
        $this->stockItemResource->getConnection()->insertOnDuplicate(
            $this->stockItemResource->getMainTable(),
            $offerData,
            array_keys($offerData)
        );

        return $entity;
    }
}

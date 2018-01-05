<?php
/**
 * Repository
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Model\Repository\Stock;

use Smile\RetailerOfferInventory\Api\Data;
use Smile\RetailerOfferInventory\Api\StockItemRepositoryInterface;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterfaceFactory;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;
use Smile\RetailerOfferInventory\Api\Data\StockItemResultsInterfaceFactory;
use Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item as StockItemResourceModel;
use Smile\RetailerOfferInventory\Model\Repository\ManagementFactory as RepositoryManagementFactory;
use Smile\RetailerOfferInventory\Model\Repository\Management as RepositoryManagement;

/**
 * Class Item
 * @package Smile\RetailerOfferInventory\Model\Repository\Stock
 */
class Item implements StockItemRepositoryInterface
{
    /**
     * @var RepositoryManagement
     */
    protected $stockItemRepositoryManagement;

    /**
     * Item constructor.
     *
     * @param StockItemInterfaceFactory $objectFactory
     * @param StockItemResourceModel $objectResource
     * @param StockItemResultsInterfaceFactory $searchResultsFactory
     * @param RepositoryManagementFactory   $repositoryManagementFactory
     */
    public function __construct(
        StockItemInterfaceFactory        $objectFactory,
        StockItemResourceModel           $objectResource,
        StockItemResultsInterfaceFactory $searchResultsFactory,
        RepositoryManagementFactory      $repositoryManagementFactory
    ) {
        $this->stockItemRepositoryManagement = $repositoryManagementFactory->create();

        $this->stockItemRepositoryManagement
            ->setObjectFactory($objectFactory)
            ->setObjectResource($objectResource)
            ->setSearchResultFactory($searchResultsFactory)
            ->setIdentifierFieldName(StockItemInterface::FIELD_OFFER_ID);
    }

    /**
     * @inheritdoc
     */
    public function getById($itemId)
    {
        return $this->stockItemRepositoryManagement->getEntityById($itemId);
    }

    /**
     * @inheritdoc
     */
    public function getByOfferId($offerId)
    {
        return $this->stockItemRepositoryManagement->getEntityByIdentifier($offerId);
    }

    /**
     * @inheritdoc
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        return $this->stockItemRepositoryManagement->getEntities($searchCriteria);
    }

    /**
     * @inheritdoc
     */
    public function save(Data\StockItemInterface $stockItem)
    {
        return $this->stockItemRepositoryManagement->saveEntity($stockItem);
    }

    /**
     * @inheritdoc
     */
    public function deleteById($itemId)
    {
        return $this->stockItemRepositoryManagement->deleteEntityById($itemId);
    }

    /**
     * @inheritdoc
     */
    public function deleteByOfferId($offerId)
    {
        return $this->stockItemRepositoryManagement->deleteEntityByIdentifier($offerId);
    }

    /**
     * @inheritdoc
     */
    public function delete(Data\StockItemInterface $stockItem)
    {
        return $this->stockItemRepositoryManagement->deleteEntity($stockItem);
    }
}

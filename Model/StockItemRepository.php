<?php

/**
 * Repository StockItemRepository
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Model;

use Exception;
use Magento\Framework\Api\AbstractExtensibleObject;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface as CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Data\Collection\AbstractDb as AbstractCollection;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Smile\RetailerOfferInventory\Api\Data;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterfaceFactory;
use Smile\RetailerOfferInventory\Api\Data\StockItemResultsInterface;
use Smile\RetailerOfferInventory\Api\Data\StockItemResultsInterfaceFactory;
use Smile\RetailerOfferInventory\Api\StockItemRepositoryInterface;
use Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item as StockItemResourceModel;

/**
 * StockItemRepository  model class
 */
class StockItemRepository implements StockItemRepositoryInterface
{
    /**
     * Item constructor.
     */
    public function __construct(
        protected StockItemInterfaceFactory        $objectFactory,
        protected StockItemResourceModel           $objectResource,
        protected StockItemResultsInterfaceFactory $searchResultsFactory,
        protected CollectionProcessor              $collectionProcessor
    ) {
    }

    /**
     * Retrieve stock item inventory.
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings("PMD.StaticAccess")
     */
    public function getById(int $itemId): StockItemInterface
    {
        /** @var \Magento\Framework\Model\AbstractModel $object */
        $object = $this->objectFactory->create();
        $this->objectResource->load($object, $itemId);

        if (!$object->getId()) {
            throw  NoSuchEntityException::singleField('objectId', $itemId);
        }

        /** @var StockItemInterface $object */
        return $object;
    }

    /**
     * Retrieve stock item inventory by offer_id.
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings("PMD.StaticAccess")
     */
    public function getByOfferId(int $offerId): StockItemInterface
    {
        /** @var \Magento\Framework\Model\AbstractModel $object */
        $object = $this->objectFactory->create();
        $this->objectResource->load($object, $offerId, StockItemInterface::FIELD_OFFER_ID);

        if (!$object->getId()) {
            throw NoSuchEntityException::singleField(StockItemInterface::FIELD_OFFER_ID, $offerId);
        }

        /** @var StockItemInterface $object */
        return $object;
    }

    /**
     * Retrieve stock item inventory matching the specified criteria.
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): StockItemResultsInterface
    {
        /** @var AbstractCollection $collection */
        $collection = $this->objectFactory->create()->getCollection();

        /** @var SearchResults $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $this->collectionProcessor->process($searchCriteria, $collection);

        $collection->load();
        $searchResults->setTotalCount($collection->getSize());

        /** @var AbstractExtensibleObject[] $collectionItems */
        $collectionItems = $collection->getItems();
        $searchResults->setItems($collectionItems);

        /** @var StockItemResultsInterface $searchResults */
        return $searchResults;
    }

    /**
     * Save stock.
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(Data\StockItemInterface $stockItem): StockItemInterface
    {
        try {
            /** @var \Magento\Framework\Model\AbstractModel $stockItem */
            $this->objectResource->save($stockItem);
        } catch (Exception $e) {
            $msg = new Phrase($e->getMessage());
            throw new CouldNotSaveException($msg);
        }

        /** @var StockItemInterface $stockItem */
        return $stockItem;
    }

    /**
     * Delete stock by ID.
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $itemId): bool
    {
        $stockItem = $this->getById($itemId);

        return $this->delete($stockItem);
    }

    /**
     * Delete stock by Offer ID.
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteByOfferId(int $offerId): bool
    {
        $stockItem = $this->getByOfferId($offerId);

        return $this->delete($stockItem);
    }

    /**
     * Delete stock.
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\StockItemInterface $stockItem): bool
    {
        try {
            /** @var \Magento\Framework\Model\AbstractModel $stockItem */
            $this->objectResource->delete($stockItem);
        } catch (Exception $e) {
            $msg = new Phrase($e->getMessage());
            throw new CouldNotDeleteException($msg);
        }

        return true;
    }
}

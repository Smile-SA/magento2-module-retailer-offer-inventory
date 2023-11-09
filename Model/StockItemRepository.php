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
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface as CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
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
 *
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class StockItemRepository implements StockItemRepositoryInterface
{
    /**
     * Item constructor.
     *
     * @param StockItemInterfaceFactory        $objectFactory        Object factory
     * @param StockItemResourceModel           $objectResource       Object resource
     * @param StockItemResultsInterfaceFactory $searchResultsFactory Search Results Factory
     * @param CollectionProcessor              $collectionProcessor  Collection Processor
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
     * @param int $itemId Item id.
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

        return $object;
    }

    /**
     * Retrieve stock item inventory by offer_id.
     *
     * @param int $offerId Offer id.
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

        return $object;
    }

    /**
     * Retrieve stock item inventory matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria Search criteria.
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): StockItemResultsInterface
    {
        /** @var AbstractCollection $collection */
        $collection = $this->objectFactory->create()->getCollection();

        /** @var \Magento\Framework\Api\SearchResults $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        if ($searchCriteria) {
            $searchResults->setSearchCriteria($searchCriteria);
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $collection->load();
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * Save stock.
     *
     * @param \Smile\RetailerOfferInventory\Api\Data\StockItemInterface $stockItem Stock item object.
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

        return $stockItem;
    }

    /**
     * Delete stock by ID.
     *
     * @param int $itemId Item id.
     * @return bool true on success
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
     * @param int $offerId Offer id.
     * @return bool true on success
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
     * @param \Smile\RetailerOfferInventory\Api\Data\StockItemInterface $stockItem Stock item object.
     * @return bool true on success
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

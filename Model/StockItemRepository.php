<?php
/**
 * Repository StockItemRepository
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Model;

use Smile\RetailerOfferInventory\Api\Data;
use Smile\RetailerOfferInventory\Api\StockItemRepositoryInterface;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterfaceFactory;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;
use Smile\RetailerOfferInventory\Api\Data\StockItemResultsInterfaceFactory;
use Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item as StockItemResourceModel;
use Magento\Framework\Phrase;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface as CollectionProcessor;
use Magento\Framework\Data\Collection\AbstractDb as AbstractCollection;

/**
 * Class StockItemRepository
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class StockItemRepository implements StockItemRepositoryInterface
{
    /**
     * @var StockItemInterfaceFactory
     */
    protected $objectFactory;

    /**
     * @var StockItemResourceModel
     */
    protected $objectResource;

    /**
     * @var StockItemResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessor
     */
    protected $collectionProcessor;

    /**
     * Item constructor.
     *
     * @param StockItemInterfaceFactory        $objectFactory
     * @param StockItemResourceModel           $objectResource
     * @param StockItemResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessor              $collectionProcessor
     */
    public function __construct(
        StockItemInterfaceFactory        $objectFactory,
        StockItemResourceModel           $objectResource,
        StockItemResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessor              $collectionProcessor
    ) {
        $this->objectFactory        = $objectFactory;
        $this->objectResource       = $objectResource;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor  = $collectionProcessor;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings("PMD.StaticAccess")
     */
    public function getById($itemId)
    {
        /** @var \Magento\Framework\Model\AbstractModel $object */
        $object = $this->objectFactory->create();
        $this->objectResource->load($object, $itemId);

        if (!$object->getId()) {
            // object does not exist
            throw  NoSuchEntityException::singleField('objectId', $itemId);
        }

        return $object;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings("PMD.StaticAccess")
     */
    public function getByOfferId($offerId)
    {
        /** @var \Magento\Framework\Model\AbstractModel $object */
        $object = $this->objectFactory->create();
        $this->objectResource->load($object, $offerId, StockItemInterface::FIELD_OFFER_ID);

        if (!$object->getId()) {
            // object does not exist
            throw NoSuchEntityException::singleField(StockItemInterface::FIELD_OFFER_ID, $offerId);
        }

        return $object;
    }

    /**
     * @inheritdoc
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var AbstractCollection $collection */
        $collection = $this->objectFactory->create()->getCollection();

        /** @var \Magento\Framework\Api\SearchResults $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        if ($searchCriteria) {
            $searchResults->setSearchCriteria($searchCriteria);
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        // load the collection
        $collection->load();

        // build the result
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function save(Data\StockItemInterface $stockItem)
    {
        try {
            /** @var \Magento\Framework\Model\AbstractModel $stockItem */
            $this->objectResource->save($stockItem);
        } catch (\Exception $e) {
            $msg = new Phrase($e->getMessage());
            throw new CouldNotSaveException($msg);
        }

        return $stockItem;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($itemId)
    {
        $stockItem = $this->getById($itemId);
        return $this->delete($stockItem);
    }

    /**
     * @inheritdoc
     */
    public function deleteByOfferId($offerId)
    {
        $stockItem = $this->getByOfferId($offerId);
        return $this->delete($stockItem);
    }

    /**
     * @inheritdoc
     */
    public function delete(Data\StockItemInterface $stockItem)
    {
        try {
            /** @var \Magento\Framework\Model\AbstractModel $stockItem */
            $this->objectResource->delete($stockItem);
        } catch (\Exception $e) {
            $msg = new Phrase($e->getMessage());
            throw new CouldNotDeleteException($msg);
        }

        return true;
    }
}

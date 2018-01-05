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

namespace Smile\RetailerOfferInventory\Model\Repository;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface as CollectionProcessor;
use Magento\Framework\Data\Collection\AbstractDb as AbstractCollection;
use Magento\Framework\Phrase;
use Magento\Framework\Model\AbstractModel;

class Management
{
    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    protected $objectResource;

    /**
     * Object Factory
     * @var mixed
     */
    protected $objectFactory;

    /**
     * Search Result Factory
     * @var mixed
     */
    protected $searchResultsFactory;

    /**
     * Repository cache by id
     *
     * @var array
     */
    protected $cacheById = [];

    /**
     * Repository cache by identifier

     * @var array
     */
    protected $cacheByIdentifier = [];

    /**
     * The identifier field name for the getByIdentifier method
     *
     * @var string|null
     */
    protected $identifierFieldName = null;

    /**
     * @var CollectionProcessor
     */
    protected $collectionProcessor;

    /**
     * AbstractRepository constructor.
     *
     * @param CollectionProcessor          $collectionProcessor
     */
    public function __construct(
        CollectionProcessor $collectionProcessor
    ) {
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Set the object factory
     *
     * @param mixed $objectFactory
     *
     * @return $this
     */
    public function setObjectFactory($objectFactory)
    {
        $this->objectFactory = $objectFactory;

        return $this;
    }

    /**
     * Set the object resource
     *
     * @param  \Magento\Framework\Model\ResourceModel\Db\AbstractDb $objectResource
     *
     * @return $this
     */
    public function setObjectResource($objectResource)
    {
        $this->objectResource = $objectResource;

        return $this;
    }

    /**
     * Set the identifier field name for the getByIdentifier method
     *
     * @param string|null $fieldName
     *
     * @return $this
     */
    public function setIdentifierFieldName($fieldName = null)
    {
        $this->identifierFieldName = $fieldName;

        return $this;
    }

    /**
     * Set the object search result factory
     *
     * @param mixed $searchResultsFactory
     *
     * @return $this
     */
    public function setSearchResultFactory($searchResultsFactory)
    {
        $this->searchResultsFactory = $searchResultsFactory;

        return $this;
    }

    /**
     * Retrieve a entity by its ID
     *
     * @param int $objectId id of the entity
     *
     * @return \Magento\Framework\Model\AbstractModel
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings("PMD.StaticAccess")
     */
    public function getEntityById($objectId)
    {
        if (!isset($this->cacheById[$objectId])) {
            /** @var \Magento\Framework\Model\AbstractModel $object */
            $object = $this->objectFactory->create();
            $this->objectResource->load($object, $objectId);

            if (!$object->getId()) {
                // object does not exist
                throw NoSuchEntityException::singleField('objectId', $objectId);
            }

            $this->cacheById[$object->getId()] = $object;

            if (!is_null($this->identifierFieldName)) {
                $objectIdentifier = $object->getData($this->identifierFieldName);
                $this->cacheByIdentifier[$objectIdentifier] = $object;
            }
        }

        return $this->cacheById[$objectId];
    }


    /**
     * Retrieve a entity by its identifier
     *
     * @param string $objectIdentifier identifier of the entity
     *
     * @return \Magento\Framework\Model\AbstractModel
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings("PMD.StaticAccess")
     */
    public function getEntityByIdentifier($objectIdentifier)
    {
        if (is_null($this->identifierFieldName)) {
            throw new NoSuchEntityException('The identifier field name is not set');
        }

        if (!isset($this->cacheByIdentifier[$objectIdentifier])) {
            /** @var \Magento\Framework\Model\AbstractModel $object */
            $object = $this->objectFactory->create();
            $this->objectResource->load($object, $objectIdentifier, $this->identifierFieldName);

            if (!$object->getId()) {
                // object does not exist
                throw NoSuchEntityException::singleField('objectIdentifier', $objectIdentifier);
            }

            $this->cacheById[$object->getId()] = $object;
            $this->cacheByIdentifier[$objectIdentifier] = $object;
        }

        return $this->cacheByIdentifier[$objectIdentifier];
    }

    /**
     * Save entity
     *
     * @param mixed $object
     *
     * @return AbstractModel
     * @throws CouldNotSaveException
     */
    public function saveEntity($object)
    {
        /** @var AbstractModel $object */
        try {
            $this->objectResource->save($object);

            unset($this->cacheById[$object->getId()]);
            if (!is_null($this->identifierFieldName)) {
                $objectIdentifier = $object->getData($this->identifierFieldName);
                unset($this->cacheByIdentifier[$objectIdentifier]);
            }
        } catch (\Exception $e) {
            $msg = new Phrase($e->getMessage());
            throw new CouldNotSaveException($msg);
        }

        return $object;
    }

    /**
     * Delete entity
     *
     * @param mixed $object
     *
     * @return boolean
     * @throws CouldNotDeleteException
     */
    public function deleteEntity($object)
    {
        try {
            $this->objectResource->delete($object);

            unset($this->cacheById[$object->getId()]);
            if (!is_null($this->identifierFieldName)) {
                $objectIdentifier = $object->getData($this->identifierFieldName);
                unset($this->cacheByIdentifier[$objectIdentifier]);
            }
        } catch (\Exception $e) {
            $msg = new Phrase($e->getMessage());
            throw new CouldNotDeleteException($msg);
        }

        return true;
    }

    /**
     * Delete entity by id
     *
     * @param int $objectId
     *
     * @return boolean
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteEntityById($objectId)
    {
        return $this->deleteEntity($this->getEntityById($objectId));
    }

    /**
     * Delete entity by identifier
     *
     * @param string $objectIdentifier
     *
     * @return boolean
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteEntityByIdentifier($objectIdentifier)
    {
        return $this->deleteEntity($this->getEntityByIdentifier($objectIdentifier));
    }

    /**
     * Retrieve not eav entities which match a specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria search criteria
     *
     * @return \Magento\Framework\Api\SearchResults
     */
    public function getEntities(SearchCriteriaInterface $searchCriteria = null)
    {
        /** @var AbstractCollection $collection */
        $collection = $this->getEntityCollection();

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
     * get the entity collection
     *
     * @return AbstractCollection
     */
    protected function getEntityCollection()
    {
        return $this->objectFactory->create()->getCollection();
    }
}

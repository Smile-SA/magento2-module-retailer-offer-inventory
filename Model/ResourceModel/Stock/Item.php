<?php

/**
 * ResourceModel Stock Item
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Model\ResourceModel\Stock;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Item resource model class
 *
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class Item extends AbstractDb
{
    /**
     * Class constructor
     *
     * @param Context       $context        Context
     * @param EntityManager $entityManager  Entity Manager
     * @param MetadataPool  $metadataPool   Metadata pool
     * @param string        $connectionName Connection name
     */
    public function __construct(
        Context       $context,
        protected EntityManager $entityManager,
        protected MetadataPool  $metadataPool,
        ?string $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * Get connection
     *
     * @throws \Exception
     */
    public function getConnection(): AdapterInterface|false
    {
        return $this->metadataPool->getMetadata(StockItemInterface::class)->getEntityConnection();
    }

    /**
     * Load an object
     *
     * @param AbstractModel $object Object
     * @param mixed         $value  Value
     * @param string        $field  Field
     * @return $this
     * @throws \Exception
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $objectId = $this->getObjectId($value, $field);

        if ($objectId) {
            $this->entityManager->load($object, $objectId);
        }

        return $this;
    }

    /**
     * Save an object
     *
     * @param AbstractModel $object Object
     * @return $this
     * @throws \Exception
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);

        return $this;
    }

    /**
     * Delete an object
     *
     * @param AbstractModel $object Object
     * @return $this
     * @throws \Exception
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);

        return $this;
    }

    /**
     * Insert or update lines
     *
     * @param array $values Values
     * @param array $fields Fields
     * @return $this
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function insertOnDuplicate(array $values, array $fields)
    {
        $this->getConnection()->insertOnDuplicate(
            $this->getMainTable(),
            $values,
            $fields
        );

        return $this;
    }

    /**
     * Get the id of an object with all table field
     *
     * @param mixed $value Value
     * @param null  $field Field
     * @throws \Exception
     */
    protected function getObjectId(mixed $value, $field = null): bool|int|string
    {
        $entityMetadata = $this->metadataPool->getMetadata(StockItemInterface::class);
        if ($field === null) {
            $field = $entityMetadata->getIdentifierField();
        }
        $entityId = $value;

        if ($field != $entityMetadata->getIdentifierField()) {
            $field = $this->getConnection()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), $field));
            $select = $this->getConnection()->select()->from($this->getMainTable())->where($field . '=?', $value);

            $select->reset(Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $entityId = count($result) ? $result[0] : false;
        }

        return $entityId;
    }

    /**
     * Magento Constructor
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct(): void
    {
        $this->_init(
            StockItemInterface::TABLE_NAME,
            StockItemInterface::FIELD_ID
        );
    }
}

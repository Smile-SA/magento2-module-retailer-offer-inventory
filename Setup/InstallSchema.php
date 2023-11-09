<?php

/**
 * Setup InstallSchema
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Smile\Offer\Api\Data\OfferInterface;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Offer Inventory schema install class.
 *
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @inheritdoc
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->createOfferInventoryTable($setup);
        $setup->endSetup();
    }

    /**
     * Create the offer inventory table.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup The Setup
     * @throws \Zend_Db_Exception
     */
    private function createOfferInventoryTable(SchemaSetupInterface $setup): void
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable(StockItemInterface::TABLE_NAME))
            ->addColumn(
                StockItemInterface::FIELD_ID,
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )
            ->addColumn(
                StockItemInterface::FIELD_OFFER_ID,
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Offer ID'
            )
            ->addColumn(
                StockItemInterface::FIELD_QTY,
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true, 'default' => '0'],
                'Qty'
            )
            ->addColumn(
                StockItemInterface::FIELD_IS_IN_STOCK,
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is in stock'
            )
            ->addForeignKey(
                $setup->getFkName(
                    StockItemInterface::TABLE_NAME,
                    StockItemInterface::FIELD_OFFER_ID,
                    'smile_offer',
                    OfferInterface::OFFER_ID
                ),
                StockItemInterface::FIELD_OFFER_ID,
                $setup->getTable('smile_offer'),
                OfferInterface::OFFER_ID,
                Table::ACTION_CASCADE
            )
            ->addIndex(
                $setup->getIdxName(
                    StockItemInterface::TABLE_NAME,
                    [StockItemInterface::FIELD_OFFER_ID],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [StockItemInterface::FIELD_OFFER_ID],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            );
        $setup->getConnection()->createTable($table);
    }
}

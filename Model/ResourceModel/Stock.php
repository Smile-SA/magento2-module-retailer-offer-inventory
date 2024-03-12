<?php

/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;
use Smile\RetailerOfferInventory\Helper\OfferInventory;

/**
 * Stock Resource Model
 */
class Stock extends AbstractDb
{
    /**
     * Stock constructor.
     */
    public function __construct(
        Context   $context,
        private OfferInventory $helper,
        ?string $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * Correct particular stock products qty based on operator
     */
    public function correctItemsQty(array $items, string $operator): void
    {
        $connection = $this->getConnection();
        $conditions = $outOfStock = $offerIds = [];
        foreach ($items as $productId => $qty) {
            $offerStock = $this->helper->getCurrentOfferStock($productId);
            if ($offerStock) {
                $case              = $connection->quoteInto('?', $offerStock->getOfferId());
                $conditions[$case] = $connection->quoteInto("qty{$operator}?", $qty);
                $offerIds[]        = $offerStock->getOfferId();

                if ($qty <= 0) {
                    $outOfStock[] = $offerStock->getOfferId();
                }
            }
        }

        if (!empty($conditions) && !empty($offerIds)) {
            $value = $connection->getCaseSql(StockItemInterface::FIELD_OFFER_ID, $conditions, 'qty');

            $connection->beginTransaction();
            $connection->update(
                $this->getTable(StockItemInterface::TABLE_NAME),
                [StockItemInterface::FIELD_QTY => $value],
                [StockItemInterface::FIELD_OFFER_ID . ' IN (?)' => $offerIds]
            );

            if (!empty($outOfStock)) {
                $connection->update(
                    $this->getTable(StockItemInterface::TABLE_NAME),
                    [StockItemInterface::FIELD_IS_IN_STOCK => '0'],
                    [StockItemInterface::FIELD_OFFER_ID . ' IN (?)' => $outOfStock]
                );
            }

            $connection->commit();
        }
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

<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\RetailerOfferInventory\Model\ResourceModel;

use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Stock Resource Model
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Stock extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Smile\RetailerOfferInventory\Helper\OfferInventory
     */
    private $helper;

    /**
     * Stock constructor.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context   $context        Context
     * @param \Smile\RetailerOfferInventory\Helper\OfferInventory $helper         Helper
     * @param null                                                $connectionName Connection name
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Smile\RetailerOfferInventory\Helper\OfferInventory $helper,
        $connectionName = null
    ) {
        $this->helper = $helper;
        parent::__construct($context, $connectionName);
    }

    /**
     * Correct particular stock products qty based on operator
     *
     * @param int[]  $items    The items to correct
     * @param string $operator +/-
     *
     * @return void
     */
    public function correctItemsQty(array $items, $operator)
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
    protected function _construct()
    {
        $this->_init(
            StockItemInterface::TABLE_NAME,
            StockItemInterface::FIELD_ID
        );
    }
}

<?php
/**
 * Rewrite CatalogInventory ResourceModel Stock
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Rewrite\CatalogInventory\Model\ResourceModel;

use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;
use Smile\RetailerOfferInventory\Helper\OfferStock as OfferStockHelper;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Stock
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class Stock extends \Magento\CatalogInventory\Model\ResourceModel\Stock
{
    /**
     * @var OfferStockHelper
     */
    private $helper;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context  $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Stdlib\DateTime\DateTime        $dateTime
     * @param StockConfigurationInterface                        $stockConfiguration
     * @param StoreManagerInterface                              $storeManager
     * @param OfferStockHelper                                   $offerStockHelper
     * @param string|null                                        $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        StockConfigurationInterface $stockConfiguration,
        StoreManagerInterface $storeManager,
        OfferStockHelper $offerStockHelper,
        $connectionName = null
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $dateTime,
            $stockConfiguration,
            $storeManager,
            $connectionName
        );

        $this->helper = $offerStockHelper;
    }

    /**
     * Update stock after place order
     *
     * {@inheritdoc}
     */
    public function correctItemsQty(array $items, $websiteId, $operator)
    {
        if (empty($items)) {
            return;
        }

        if (!$this->helper->useStoreOffers()) {
            parent::correctItemsQty($items, $websiteId, $operator);
        }

        if ($this->helper->useStoreOffers()) {
            $connection = $this->getConnection();
            $conditions = $outOfStock = $offerIds = [];

            foreach ($items as $productId => $qty) {
                $offerStock  = $this->helper->getCurrentOfferStock($productId);
                $case = $connection->quoteInto('?', $offerStock->getOfferId());
                $result = $connection->quoteInto("qty{$operator}?", $qty);
                $conditions[$case] = $result;
                $offerIds[] = $offerStock->getOfferId();

                if ($qty <= 0) {
                    $outOfStock[] = $offerStock->getOfferId();
                }
            }

            $value = $connection->getCaseSql(
                StockItemInterface::FIELD_OFFER_ID,
                $conditions,
                'qty'
            );

            $where = [StockItemInterface::FIELD_OFFER_ID . ' IN (?)' => $offerIds];

            $connection->beginTransaction();
            $connection->update(
                $this->getTable(StockItemInterface::TABLE_NAME),
                [StockItemInterface::FIELD_QTY => $value],
                $where
            );


            if (!empty($outOfStock)) {
                $this->setOutOfStock($outOfStock);
            }

            $connection->commit();
        }
    }

    /**
     * Set items out of stock basing on their quantities and config settings
     *
     * @param array $outOfStock
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return void
     */
    protected function setOutOfStock($outOfStock)
    {
        $this->helper->flushOfferStockCache($outOfStock);

        $connection = $this->getConnection();
        $connection->update(
            $this->getTable(StockItemInterface::TABLE_NAME),
            [StockItemInterface::FIELD_IS_IN_STOCK => '0'],
            [StockItemInterface::FIELD_OFFER_ID . ' IN (?)' => $outOfStock]
        );
    }
}

<?php
/**
 * Model Stock Item
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Model\Stock;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Class Item
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class Item extends AbstractModel implements StockItemInterface, IdentityInterface
{
    const CACHE_TAG = 'retailer_offer_inventory_stock_item';

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getItemId()];
    }

    /**
     * Get item_id
     *
     * @return int
     */
    public function getItemId()
    {
        return $this->getId();
    }

    /**
     * Get offer_id
     *
     * @return int
     */
    public function getOfferId()
    {
        return (int) $this->getData(self::FIELD_OFFER_ID);
    }

    /**
     * Get qty
     *
     * @return int
     */
    public function getQty()
    {
        return (int) $this->getData(self::FIELD_QTY);
    }

    /**
     * Get is_in_stock
     *
     * @return int
     */
    public function getIsInStock()
    {
        return (int) $this->getData(self::FIELD_IS_IN_STOCK);
    }

    /**
     * Set field: item_id
     *
     * @param int $value Item id.
     *
     * @return StockItemInterface
     */
    public function setItemId($value)
    {
        return $this->setId((int) $value);
    }

    /**
     * Set field: offer_id
     *
     * @param int $value Offer id.
     *
     * @return StockItemInterface
     */
    public function setOfferId($value)
    {
        return $this->setData(self::FIELD_OFFER_ID, (int) $value);
    }

    /**
     * Set field: qty
     *
     * @param int $value Quantity.
     *
     * @return StockItemInterface
     */
    public function setQty($value)
    {
        return $this->setData(self::FIELD_QTY, (int) $value);
    }

    /**
     * Set field: is_in_stock
     *
     * @param int $value Is in stock.
     *
     * @return StockItemInterface
     */
    public function setIsInStock($value)
    {
        return $this->setData(self::FIELD_IS_IN_STOCK, (int) $value);
    }

    /**
     * Internal Constructor
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(\Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item::class);
    }
}

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

class Item extends AbstractModel implements StockItemInterface, IdentityInterface
{
    const CACHE_TAG = 'retailer_offer_inventory_stock_item';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item::class);
    }

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
     * @inheritdoc
     */
    public function getItemId()
    {
        return $this->getId();
    }

    /**
     * @inheritdoc
     */
    public function getOfferId()
    {
        return (int) $this->getData(self::FIELD_OFFER_ID);
    }

    /**
     * @inheritdoc
     */
    public function getQty()
    {
        return (int) $this->getData(self::FIELD_QTY);
    }

    /**
     * @inheritdoc
     */
    public function getIsInStock()
    {
        return (int) $this->getData(self::FIELD_IS_IN_STOCK);
    }

    /**
     * @inheritdoc
     */
    public function setItemId($value)
    {
        return $this->setId((int) $value);
    }

    /**
     * @inheritdoc
     */
    public function setOfferId($value)
    {
        return $this->setData(self::FIELD_OFFER_ID, (int) $value);
    }

    /**
     * @inheritdoc
     */
    public function setQty($value)
    {
        return $this->setData(self::FIELD_QTY, (int) $value);
    }

    /**
     * @inheritdoc
     */
    public function setIsInStock($value)
    {
        return $this->setData(self::FIELD_IS_IN_STOCK, (int) $value);
    }
}

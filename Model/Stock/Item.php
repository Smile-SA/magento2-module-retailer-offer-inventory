<?php

/**
 * Model Stock Item
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Model\Stock;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Item model class
 */
class Item extends AbstractModel implements StockItemInterface, IdentityInterface
{
    public const CACHE_TAG = 'retailer_offer_inventory_stock_item';

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getItemId()];
    }

    /**
     * Get item_id
     */
    public function getItemId(): int
    {
        return $this->getId();
    }

    /**
     * Get offer_id
     */
    public function getOfferId(): int
    {
        return (int) $this->getData(self::FIELD_OFFER_ID);
    }

    /**
     * Get qty
     */
    public function getQty(): int
    {
        return (int) $this->getData(self::FIELD_QTY);
    }

    /**
     * Get is_in_stock
     */
    public function getIsInStock(): int
    {
        return (int) $this->getData(self::FIELD_IS_IN_STOCK);
    }

    /**
     * Set field: item_id
     */
    public function setItemId(int $value): StockItemInterface
    {
        return $this->setId((int) $value);
    }

    /**
     * Set field: offer_id
     */
    public function setOfferId(int $value): StockItemInterface
    {
        return $this->setData(self::FIELD_OFFER_ID, (int) $value);
    }

    /**
     * Set field: qty
     */
    public function setQty(int $value): StockItemInterface
    {
        return $this->setData(self::FIELD_QTY, (int) $value);
    }

    /**
     * Set field: is_in_stock
     */
    public function setIsInStock(int $value): StockItemInterface
    {
        return $this->setData(self::FIELD_IS_IN_STOCK, (int) $value);
    }

    /**
     * Internal Constructor
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct(): void
    {
        $this->_init(\Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item::class);
    }
}

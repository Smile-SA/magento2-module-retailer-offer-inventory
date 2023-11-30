<?php

/**
 * Api data
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Api\Data;

/**
 * Interface StockItemInterface
 */
interface StockItemInterface
{
    public const TABLE_NAME         = 'smile_offer_inventory_stock_item';
    public const FIELD_ID           = 'item_id';
    public const FIELD_OFFER_ID     = 'offer_id';
    public const FIELD_QTY          = 'qty';
    public const FIELD_IS_IN_STOCK  = 'is_in_stock';

    /**
     * Get item_id
     *
     * @return int
     */
    public function getItemId();

    /**
     * Get offer_id
     *
     * @return int
     */
    public function getOfferId();

    /**
     * Get qty
     *
     * @return int
     */
    public function getQty();

    /**
     * Get is_in_stock
     *
     * @return int
     */
    public function getIsInStock();

    /**
     * Set field: item_id
     *
     * @return StockItemInterface
     */
    public function setItemId(int $value): StockItemInterface;

    /**
     * Set field: offer_id
     *
     * @return StockItemInterface
     */
    public function setOfferId(int $value): StockItemInterface;

    /**
     * Set field: qty
     *
     * @return StockItemInterface
     */
    public function setQty(int $value): StockItemInterface;

    /**
     * Set field: is_in_stock
     *
     * @return StockItemInterface
     */
    public function setIsInStock(int $value): StockItemInterface;
}

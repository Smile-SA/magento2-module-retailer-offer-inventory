<?php
/**
 * Api data
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Api\Data;

/**
 * Interface StockItemInterface
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
interface StockItemInterface
{
    const TABLE_NAME         = 'smile_retailer_inventory_stock_item';
    const FIELD_ID           = 'item_id';
    const FIELD_OFFER_ID     = 'offer_id';
    const FIELD_QTY          = 'qty';
    const FIELD_IS_IN_STOCK  = 'is_in_stock';

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
     * @param int $value Item id.
     *
     * @return StockItemInterface
     */
    public function setItemId($value);

    /**
     * Set field: offer_id
     *
     * @param int $value Offer id.
     *
     * @return StockItemInterface
     */
    public function setOfferId($value);

    /**
     * Set field: qty
     *
     * @param int $value Quantity.
     *
     * @return StockItemInterface
     */
    public function setQty($value);

    /**
     * Set field: is_in_stock
     *
     * @param int $value Is in stock.
     *
     * @return StockItemInterface
     */
    public function setIsInStock($value);
}

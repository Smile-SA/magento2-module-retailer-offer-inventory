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

use Magento\Framework\Api\SearchResultsInterface;

interface StockItemResultsInterface extends SearchResultsInterface
{
    /**
     * Get stock list.
     *
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemInterface[]
     */
    public function getItems();

    /**
     * Set stock list.
     *
     * @param \Smile\RetailerOfferInventory\Api\Data\StockItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items);

    /**
     * Get search criteria.
     *
     * @return \Magento\CatalogInventory\Api\StockItemCriteriaInterface
     */
    public function getSearchCriteria();
}

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

use Magento\CatalogInventory\Api\StockItemCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface StockItemResultsInterface
 */
interface StockItemResultsInterface extends SearchResultsInterface
{
    /**
     * Get stock list.
     *
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemInterface[]
     */
    public function getItems(): array;

    /**
     * Set stock list.
     *
     * @param \Smile\RetailerOfferInventory\Api\Data\StockItemInterface[] $items Items.
     * @return $this
     */
    public function setItems(array $items): self;

    /**
     * Get search criteria.
     *
     * @return StockItemCriteriaInterface
     */
    public function getSearchCriteria(): StockItemCriteriaInterface;
}

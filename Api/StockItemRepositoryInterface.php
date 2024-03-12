<?php

/**
 * Api
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface StockItemRepositoryInterface
 */
interface StockItemRepositoryInterface
{
    /**
     * Retrieve stock item inventory.
     *
     * @param int $itemId Item id.
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $itemId): Data\StockItemInterface;

    /**
     * Retrieve stock item inventory by offer_id.
     *
     * @param int $offerId Offer id.
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByOfferId(int $offerId): Data\StockItemInterface;

    /**
     * Retrieve stock item inventory matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria Search criteria.
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Data\StockItemResultsInterface;

    /**
     * Save stock.
     *
     * @param \Smile\RetailerOfferInventory\Api\Data\StockItemInterface $stockItem Stock item object.
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(Data\StockItemInterface $stockItem): Data\StockItemInterface;

    /**
     * Delete stock by ID.
     *
     * @param int $itemId Item id.
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $itemId): bool;

    /**
     * Delete stock by Offer ID.
     *
     * @param int $offerId Offer id.
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteByOfferId(int $offerId): bool;

    /**
     * Delete stock.
     *
     * @param \Smile\RetailerOfferInventory\Api\Data\StockItemInterface $stockItem Stock item object.
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\StockItemInterface $stockItem): bool;
}

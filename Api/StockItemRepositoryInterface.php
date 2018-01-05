<?php
/**
 * Api
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Api;

interface StockItemRepositoryInterface
{
    /**
     * Retrieve stock item inventory.
     *
     * @param int $itemId
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($itemId);

    /**
     * Retrieve stock item inventory by offer_id.
     *
     * @param int $offerId
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByOfferId($offerId);

    /**
     * Retrieve stock item inventory matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Save stock.
     *
     * @param \Smile\RetailerOfferInventory\Api\Data\StockItemInterface $stockItem
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(Data\StockItemInterface $stockItem);

    /**
     * Delete stock by ID.
     *
     * @param int $itemId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($itemId);

    /**
     * Delete stock by Offer ID.
     *
     * @param int $offerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteByOfferId($offerId);

    /**
     * Delete stock.
     *
     * @param \Smile\RetailerOfferInventory\Api\Data\StockItemInterface $stockItem
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\StockItemInterface $stockItem);
}

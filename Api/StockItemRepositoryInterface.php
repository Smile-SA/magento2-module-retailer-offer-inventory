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

/**
 * Interface StockItemRepositoryInterface
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
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
    public function getById($itemId);

    /**
     * Retrieve stock item inventory by offer_id.
     *
     * @param int $offerId Offer id.
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByOfferId($offerId);

    /**
     * Retrieve stock item inventory matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria Search criteria.
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Save stock.
     *
     * @param \Smile\RetailerOfferInventory\Api\Data\StockItemInterface $stockItem Stock item object.
     * @return \Smile\RetailerOfferInventory\Api\Data\StockItemInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(Data\StockItemInterface $stockItem);

    /**
     * Delete stock by ID.
     *
     * @param int $itemId Item id.
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($itemId);

    /**
     * Delete stock by Offer ID.
     *
     * @param int $offerId Offer id.
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteByOfferId($offerId);

    /**
     * Delete stock.
     *
     * @param \Smile\RetailerOfferInventory\Api\Data\StockItemInterface $stockItem Stock item object.
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\StockItemInterface $stockItem);
}

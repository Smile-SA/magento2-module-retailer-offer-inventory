<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\RetailerOfferInventory\Plugin;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Offer Collection Plugin.
 * Used to compute inventory data with a join.
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class OfferCollectionPlugin
{
    /**
     * @var \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface
     */
    private $joinProcessor;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $joinProcessor Extension Attribute Join Processor
     */
    public function __construct(\Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $joinProcessor)
    {
        $this->joinProcessor = $joinProcessor;
    }

    /**
     * Append inventory loading to the offer collection.
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param \Smile\Offer\Model\ResourceModel\Offer\Collection $collection Collection loaded.
     * @param \Closure                                          $proceed    Original method.
     * @param bool                                              $printQuery Print queries used to load the collection.
     * @param bool                                              $logQuery   Log queries used to load the collection.
     *
     * @return \Smile\Retailer\Model\ResourceModel\Retailer\Collection
     */
    public function aroundLoad(
        \Smile\Offer\Model\ResourceModel\Offer\Collection $collection,
        \Closure $proceed,
        $printQuery = false,
        $logQuery = false
    ) {
        if (!$collection->isLoaded()) {
            \Magento\Framework\Profiler::start('SmileRetailerOffer:INVENTORY_DATA');

            // Process joining for Address : defined via extension_attributes.xml file.
            $this->joinProcessor->process($collection);

            $proceed($printQuery, $logQuery);

            $entityType = get_class($collection->getNewEmptyItem());
            foreach ($collection->getItems() as $currentItem) {
                // Process hydrating Item data with the extension attributes Data.
                $data = $this->joinProcessor->extractExtensionAttributes($entityType, $currentItem->getData());
                if (isset($data[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY])) {
                    $currentItem->setExtensionAttributes($data[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);
                }
            }
            \Magento\Framework\Profiler::stop('SmileRetailerOffer:INVENTORY_DATA');
        }

        return $collection;
    }
}

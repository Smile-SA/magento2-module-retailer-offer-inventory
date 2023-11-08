<?php

/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Plugin;

use Closure;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Profiler;
use Smile\Offer\Model\ResourceModel\Offer\Collection;

/**
 * Offer Collection Plugin.
 * Used to compute inventory data with a join.
 *
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class OfferCollectionPlugin
{
    /**
     * Constructor.
     *
     * @param JoinProcessorInterface $joinProcessor Extension Attribute Join Processor
     */
    public function __construct(private JoinProcessorInterface $joinProcessor)
    {
    }

    /**
     * Append inventory loading to the offer collection.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param Collection $collection Collection loaded.
     * @param \Closure                                          $proceed    Original method.
     * @param bool                                              $printQuery Print queries used to load the collection.
     * @param bool                                              $logQuery   Log queries used to load the collection.
     */
    public function aroundLoad(
        Collection $collection,
        Closure $proceed,
        bool $printQuery = false,
        bool $logQuery = false
    ): \Smile\Retailer\Model\ResourceModel\Retailer\Collection|Collection {
        if (!$collection->isLoaded()) {
            Profiler::start('SmileRetailerOffer:INVENTORY_DATA');

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
            Profiler::stop('SmileRetailerOffer:INVENTORY_DATA');
        }

        return $collection;
    }
}

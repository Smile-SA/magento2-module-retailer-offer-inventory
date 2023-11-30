<?php

/**
 * ResourceModel Stock Item
 *
 * @category  Smile
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Smile\RetailerOfferInventory\Model\Stock\Item;

/**
 * ResourceModel Collection class
 */
class Collection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct(): void
    {
        $this->_init(
            Item::class,
            \Smile\RetailerOfferInventory\Model\ResourceModel\Stock\Item::class
        );
    }
}

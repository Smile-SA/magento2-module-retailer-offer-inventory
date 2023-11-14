<?php

/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade Smile Elastic Suite to newer
 * versions in the future.
 *
 * @category  Smile
 * @author    Maxime Leclercq <maxime.leclercq@smile.fr>
 * @copyright 2021 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

declare(strict_types=1);

namespace Smile\RetailerOfferInventory\Model\Product\Search\Request\Container\Filter;

use Smile\ElasticsuiteCore\Api\Search\Request\Container\FilterInterface;
use Smile\ElasticsuiteCore\Search\Request\Query\QueryFactory;
use Smile\ElasticsuiteCore\Search\Request\QueryInterface;

/**
 * Class OfferIsInStock
 * Allows you to retrieve the filter on offers in stock.
 */
class OfferIsInStock implements FilterInterface
{
    /**
     * OfferIsInStock constructor.
     */
    public function __construct(
        protected QueryFactory $queryFactory
    ) {
    }

    /**
     * Return the offer is_in_stock filter.
     */
    public function getFilterQuery(): ?QueryInterface
    {
        return $this->queryFactory->create(
            QueryInterface::TYPE_TERM,
            ['field' => 'offer.is_in_stock', 'value' => true]
        );
    }
}

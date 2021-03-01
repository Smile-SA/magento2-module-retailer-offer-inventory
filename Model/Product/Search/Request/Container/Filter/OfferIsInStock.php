<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade Smile Elastic Suite to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\RetailerOffer
 * @author    Maxime Leclercq <maxime.leclercq@smile.fr>
 * @copyright 2021 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
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
     *
     * @param QueryFactory $queryFactory Query Factory
     */
    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    /**
     * Return the offer is_in_stock filter.
     *
     * @return QueryInterface|null
     */
    public function getFilterQuery()
    {
        return $this->queryFactory->create(
            QueryInterface::TYPE_TERM,
            ['field' => 'offer.is_in_stock', 'value' => true]
        );
    }
}

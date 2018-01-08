<?php
/**
 * Plugin LayerPlugin
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Plugin;

use Smile\ElasticsuiteCore\Search\Request\QueryInterface;
use Smile\Offer\Api\Data\OfferInterface;
use Smile\RetailerOffer\Plugin\AbstractPlugin;
use Smile\ElasticsuiteCatalog\Model\ResourceModel\Product\Fulltext\Collection;
use Smile\RetailerOfferInventory\Api\Data\StockItemInterface;

/**
 * Class LayerPlugin
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class LayerPlugin extends AbstractPlugin
{
    /**
     * {@inheritDoc}
     */
    public function beforePrepareProductCollection(
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection $collection
    ) {
        $this->applyStockStatusLimitationToCollection($collection);
    }

    /**
     * Filter in stock product collection
     *
     * @param Collection $collection Product Collection
     *
     * @return mixed
     */
    protected function applyStockStatusLimitationToCollection($collection)
    {
        $retailerId = $this->getRetailerId();

        if ($retailerId) {
            $sellerIdFilter = $this->queryFactory->create(
                QueryInterface::TYPE_TERM,
                ['field' => 'offer.seller_id', 'value' => $retailerId]
            );
            $mustClause     = ['must' => [$sellerIdFilter]];

            if (false === $this->isEnabledShowOutOfStock()) {
                $isAvailableFilter    = $this->queryFactory->create(
                    QueryInterface::TYPE_TERM,
                    ['field' => 'offer.is_available', 'value' => true]
                );
                $mustClause['must'][] = $isAvailableFilter;
            }

            $isInStockFilter    = $this->queryFactory->create(
                QueryInterface::TYPE_TERM,
                ['field' => 'offer.is_in_stock', 'value' => "1"]
            );
            $mustClause['must'][] = $isInStockFilter;


            $boolFilter   = $this->queryFactory->create(
                QueryInterface::TYPE_BOOL,
                $mustClause
            );
            $nestedFilter = $this->queryFactory->create(
                QueryInterface::TYPE_NESTED,
                ['path' => 'offer', 'query' => $boolFilter]
            );

            $collection->addQueryFilter($nestedFilter);
        }
    }
}

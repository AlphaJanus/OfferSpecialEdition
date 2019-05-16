<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-01-28
 * Time: 11:56
 */

namespace Netzexpert\OfferSpecialEdition\Model\ResourceModel\Quote;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchResultsInterface;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    implements \Magento\Framework\Api\Search\SearchResultInterface
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Netzexpert\OfferSpecialEdition\Model\Offer', 'Netzexpert\OfferSpecialEdition\Model\ResourceModel\Offer');
    }

    public function setItems(array $items = null)
    {
        // TODO: Implement setItems() method.
    }

    public function getAggregations()
    {
        // TODO: Implement getAggregations() method.
    }

    public function setAggregations($aggregations)
    {
        // TODO: Implement setAggregations() method.
    }

    public function getSearchCriteria()
    {
        // TODO: Implement getSearchCriteria() method.
    }

    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        // TODO: Implement setSearchCriteria() method.
    }

    public function getTotalCount()
    {
        // TODO: Implement getTotalCount() method.
    }

    public function setTotalCount($totalCount)
    {
        // TODO: Implement setTotalCount() method.
    }
}
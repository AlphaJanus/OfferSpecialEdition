<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-02-05
 * Time: 17:26
 */

namespace Netzexpert\OfferSpecialEdition\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Netzexpert\OfferSpecialEdition\Api\Data\OfferItemInterface;

interface OfferItemRepositoryInterface
{
    /**
     * @param $id
     * @return \Netzexpert\OfferSpecialEdition\Api\Data\OfferItemInterface
     */
    public function getById($id);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param OfferItemInterface $offerItem
     * @return mixed
     */
    public function save(OfferItemInterface $offerItem);

    /**
     * @param OfferItemInterface $offerItem
     * @return mixed
     */
    public function delete(OfferItemInterface $offerItem);

    /**
     * @param int $id
     * @return boolean
     */
    public function deleteById($id);
}
<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-02-05
 * Time: 17:06
 */

namespace Netzexpert\OfferSpecialEdition\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Netzexpert\OfferSpecialEdition\Api\Data\OfferInterface;

interface OfferRepositoryInterface
{
    /**
     * @param int $id
     * @return boolean
     */
    public function get($id);

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param OfferInterface $offer
     * @return mixed
     */
    public function save(OfferInterface $offer);

    /**
     * @param OfferInterface $offer
     * @return mixed
     */
    public function delete(OfferInterface $offer);

    /**
     * @param int $id
     * @return boolean
     */
    public function deleteById($id);
}
<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-01-28
 * Time: 11:53
 */

namespace Netzexpert\OfferSpecialEdition\Model\ResourceModel;


class Offer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('offer_quote', 'entity_id');
    }
}
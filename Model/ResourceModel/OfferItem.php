<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-02-05
 * Time: 17:54
 */

namespace Netzexpert\OfferSpecialEdition\Model\ResourceModel;


class OfferItem extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('offer_quote_item', 'id');
    }
}
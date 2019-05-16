<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-02-08
 * Time: 15:16
 */

namespace Netzexpert\OfferSpecialEdition\Model;

use Netzexpert\OfferSpecialEdition\Api\Data\OfferItemInterface;

class OfferItem extends \Magento\Framework\Model\AbstractModel implements OfferItemInterface,
    \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'offer_quote_item';

    public function _construct()
    {
        $this->_init('\Netzexpert\OfferSpecialEdition\Model\ResourceModel\OfferItem');
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     * @return int|mixed|null
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @param mixed $Id
     * @return \Magento\Framework\Model\AbstractModel|OfferItemInterface|OfferItem
     */
    public function setId($Id)
    {
        return $this->setData(self::ID, $Id);
    }

    /**
     * Get Offer ID
     * @return int|OfferItem
     */
    public function getOfferId()
    {
        return $this->getData(self::OFFER_ID);
    }

    /**
     * Set Offer ID
     * @param $offerId
     * @return OfferItemInterface|OfferItem
     */
    public function setOfferId($offerId)
    {
        return $this->setData(self::OFFER_ID, $offerId);
    }

    /**
     * Get Quote Item ID
     * @return int|mixed|OfferItem
     */
    public function getQuoteItemId()
    {
        return $this->getData(self::QUOTE_ITEM_ID);
    }

    /**
     * Set Quote Item ID
     * @param $quoteItem
     * @return OfferItemInterface|OfferItem
     */
    public function setQuoteItemId($quoteItem)
    {
        return $this->setData(self::QUOTE_ITEM_ID, $quoteItem);
    }
}
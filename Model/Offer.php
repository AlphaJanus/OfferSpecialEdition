<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-01-28
 * Time: 11:58
 */

namespace Netzexpert\OfferSpecialEdition\Model;

use Netzexpert\OfferSpecialEdition\Api\Data\OfferInterface;

class Offer extends \Magento\Framework\Model\AbstractModel implements OfferInterface,
    \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'offer_quote';

    public function _construct()
    {
        $this->_init('Netzexpert\OfferSpecialEdition\Model\ResourceModel\Offer');
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get Entity ID
     * @return int|mixed|null
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /** Set Entity ID
     * @param $entityId
     * @return \Magento\Framework\Model\AbstractModel|mixed|OfferInterface
     */
    public function setId($entityId)
    {
        return $this->setData(self::ID, $entityId);
    }

    /** Get Quote ID
     * @return int|mixed
     */
    public function getQuoteId()
    {
        return $this->getData(self::QUOTE_ID);
    }

    /**
     * @param $quoteId
     * @return OfferInterface|Offer
     */
    public function setQuoteId($quoteId)
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    /** Get Customer ID
     * @return \Magento\Framework\Model\AbstractModel|mixed|OfferInterface
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /** Set Customer ID
     * @param $customerId
     * @return OfferInterface|Offer
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get Email
     * @return mixed
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Set Email
     * @param $email
     * @return \Netzexpert\OfferSpecialEdition\Api\Data\OfferInterface
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Get Responsible User Id
     * @return mixed
     */
    public function getApprovedByUserId()
    {
        return $this->getData(self::APPROVED_BY_USER_ID);
    }

    /**
     * Set Responsible User Id
     * @param $approvedByUserId
     * @return mixed|Offer
     */
    public function setApprovedByUserId($approvedByUserId)
    {
        return $this->setData(self::APPROVED_BY_USER_ID, $approvedByUserId);
    }
}
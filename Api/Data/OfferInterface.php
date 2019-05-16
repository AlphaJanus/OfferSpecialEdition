<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-01-28
 * Time: 11:59
 */

namespace Netzexpert\OfferSpecialEdition\Api\Data;

interface OfferInterface
{
    /**
     * Constants for data arrays.
     */
    const ID                    = 'entity_id';
    const QUOTE_ID              = 'quote_id';
    const CUSTOMER_ID           = 'customer_id';
    const EMAIL                 = 'email';
    const APPROVED_BY_USER_ID   = 'approved_by_user_id';

    /**
     * Get Id
     * @return int|null
     */
    public function getId();

    /**
     * Set id
     * @param $entityId
     * @return \Netzexpert\OfferSpecialEdition\Api\Data\OfferInterface
     */
    public function setId($entityId);

    /**
     * Get Quote ID
     * @return int
     */
    public function getQuoteId();

    /**
     * Set Quote ID
     * @param $quoteId
     * @return \Netzexpert\OfferSpecialEdition\Api\Data\OfferInterface
     */
    public function setQuoteId($quoteId);

    /**
     * Get Email
     * @return mixed
     */
    public function getEmail();

    /**
     * Set Email
     * @param $email
     * @return \Netzexpert\OfferSpecialEdition\Api\Data\OfferInterface
     */
    public function setEmail($email);

    /**
     * Get Customer ID
     * @return mixed
     */
    public function getCustomerId();

    /**
     * Set Customer Id
     * @param $customerId
     * @return \Netzexpert\OfferSpecialEdition\Api\Data\OfferInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get Responsible User Id
     * @return mixed
     */
    public function getApprovedByUserId();

    /**
     * Set Responsible User Id
     * @param $approvedByUserId
     * @return mixed
     */
    public function setApprovedByUserId($approvedByUserId);
}
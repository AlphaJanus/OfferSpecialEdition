<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-02-05
 * Time: 17:20
 */

namespace Netzexpert\OfferSpecialEdition\Api\Data;

interface OfferItemInterface
{
    /**
     * Constants for data arrays.
     */
    const ID           = 'id';
    const OFFER_ID     = 'offer_id';
    const QUOTE_ITEM_ID  = 'quote_item_id';

    /**
     * Get Id
     * @return int|null
     */
    public function getId();

    /**
     * Set id
     * @param $Id
     * @return \Netzexpert\OfferSpecialEdition\Api\Data\OfferItemInterface
     */
    public function setId($Id);

    /**
     * Get Offer ID
     * @return int
     */
    public function getOfferId();

    /**
     * Set Offer ID
     * @param $offerId
     * @return \Netzexpert\OfferSpecialEdition\Api\Data\OfferItemInterface
     */
    public function setOfferId($offerId);

    /**
     * Get Quote Item
     * @return int|mixed
     */
    public function getQuoteItemId();

    /**
     * Set Quote Item ID
     * @param $quoteItem
     * @return \Netzexpert\OfferSpecialEdition\Api\Data\OfferItemInterface
     */
    public function setQuoteItemId($quoteItem);
}
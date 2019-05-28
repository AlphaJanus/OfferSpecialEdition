<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-05-24
 * Time: 16:13
 */

namespace Netzexpert\OfferSpecialEdition\Model;


use Magento\Checkout\Model\Session;

class IsOfferConfirmService
{
    private $checkoutSession;

    private $offerRepository;

    public function __construct(
        Session $session,
        OfferRepository $offerRepository
    ) {
        $this->offerRepository = $offerRepository;
        $this->checkoutSession = $session;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $quote = $this->checkoutSession->getQuoteId();
        $currentQuote = $this->checkoutSession->getQuote();
        $offer = $this->offerRepository->getById($currentQuote->getData('offer_id'));
        if (!$offer) {
            return false;
        } else
            if ($offer->getData('is_order') == 1) {
                return true;
            }
        return false;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-05-20
 * Time: 15:32
 */

namespace Netzexpert\OfferSpecialEdition\Block\Checkout\Cart;

use Magento\Checkout\Block\Onepage\Link;
use Magento\Checkout\Model\Session;
use Netzexpert\OfferSpecialEdition\Model\OfferRepository;

class ButtonCheck extends Link
{
    private $offerRepository;

    private $session;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        OfferRepository $offerRepository,
        array $data = []
    )
    {
        $this->offerRepository = $offerRepository;
        parent::__construct(
            $context,
            $checkoutSession,
            $checkoutHelper,
            $data
        );
    }

    public function checkUserPermissions() {
        $quote = $this->_checkoutSession->getQuoteId();
        $currentQuote = $this->_checkoutSession->getQuote();
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

<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-05-10
 * Time: 11:21
 */

namespace Netzexpert\OfferSpecialEdition\Plugin\Cart;

class CheckParamPlugin
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $session;

    /**
     * CheckParamPlugin constructor.
     * @param \Magento\Checkout\Model\Session $session
     */
    public function __construct(
        \Magento\Checkout\Model\Session $session
    )
    {
        $this->session = $session;
    }

    /**
     * @param \Magento\Checkout\Block\Onepage\Link $link
     * @return \Magento\Checkout\Block\Onepage\Link
     */
    public function afterIsPossibleOnepageCheckout(
        \Magento\Checkout\Block\Onepage\Link $link
    )
    {
        $quote = $this->session->getQuote();
        $param = $quote->getData('is_order');
        if (
            $param == '1'
        )
            return $link;
    }
}
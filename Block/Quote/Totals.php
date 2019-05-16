<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-01-22
 * Time: 17:18
 */

namespace Netzexpert\OfferSpecialEdition\Block\Quote;

use Magento\Framework\Pricing\Helper\Data;
use Magento\Quote\Model\Quote\Address\Total;

class Totals extends \Magento\Checkout\Block\Cart\Totals
{
    /** @var Data  */
    private $pricingHelper;

    /**
     * Totals constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\Config $salesConfig
     * @param Data $pricingHelper
     * @param array $layoutProcessors
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\Config $salesConfig,
        Data $pricingHelper,
        array $layoutProcessors = [],
        array $data             = []
    )
    {
        $this->pricingHelper    = $pricingHelper;
        parent::__construct(
            $context,
            $customerSession,
            $checkoutSession,
            $salesConfig,
            $layoutProcessors,
            $data
        );
    }

    /**
     * @param $total Total
     * @return float|string
     */
    public function formatValue($total) {
        return $this->pricingHelper->currencyByStore(
            $total->getData('value'),
            $this->getQuote()->getStore(),
            true,
            false);
    }
}
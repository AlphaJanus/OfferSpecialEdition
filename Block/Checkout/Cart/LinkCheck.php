<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-05-22
 * Time: 17:28
 */

namespace Netzexpert\OfferSpecialEdition\Block\Checkout\Cart;

use Magento\Checkout\Block\Cart;
use Magento\Checkout\Block\Onepage\Link;
use Magento\Checkout\Model\Session;
use Netzexpert\OfferSpecialEdition\Model\IsOfferConfirmService;
use Netzexpert\OfferSpecialEdition\Model\OfferRepository;

class LinkCheck extends Cart
{
    private $isOfferConfirmService;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrlBuilder,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Framework\App\Http\Context $httpContext,
        IsOfferConfirmService $isOfferConfirmService,
        array $data = []
    ) {
        $this->isOfferConfirmService = $isOfferConfirmService;
        parent::__construct(
            $context,
            $customerSession,
            $checkoutSession,
            $catalogUrlBuilder,
            $cartHelper,
            $httpContext,
            $data
        );
    }

    public function checkUserPermissions() {
        return $this->isOfferConfirmService->execute();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-05-24
 * Time: 17:19
 */

namespace Netzexpert\OfferSpecialEdition\Plugin\Cart;

use Netzexpert\OfferSpecialEdition\Model\IsOfferConfirmService;

class DataPlugin
{
    private $IsOfferConfirmService;

    public function __construct(
        IsOfferConfirmService $isOfferConfirmService
    )
    {
        $this->IsOfferConfirmService = $isOfferConfirmService;
    }

    public function aftercanOnepageCheckout(
        \Magento\Checkout\Helper\Data $helper, $result
    )
    {
        if (!$result) {
            return $result;
        }
        if(!$this->IsOfferConfirmService->execute()) {
            return false;
        }
        return $result;

    }
}
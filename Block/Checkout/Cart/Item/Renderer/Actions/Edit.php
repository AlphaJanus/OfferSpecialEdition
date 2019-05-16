<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-03-15
 * Time: 10:21
 */

namespace Netzexpert\OfferSpecialEdition\Block\Checkout\Cart\Item\Renderer\Actions;

use Magento\Checkout\Block\Cart\Item\Renderer\Actions\Generic;

class Edit extends Generic
{
    /**
     * Get item configure url
     *
     * @return string
     */
    public function getConfigureUrl()
    {
        return $this->getUrl(
            'checkout/cart/configure/',
            [
                'id' => $this->getItem()->getId(),
                'product_id' => $this->getItem()->getProduct()->getId(),
                'is_offer' => true
            ]
        );
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-01-14
 * Time: 14:08
 */

namespace Netzexpert\OfferSpecialEdition\Controller\Quote;

use Magento\Quote\Api\Data\CartItemInterfaceFactory;
use Netzexpert\OfferSpecialEdition\Model\CopyOffer;

class Duplicate extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var CopyOffer
     */
    private $copyQuote;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        CopyOffer $copyQuote,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\ResponseFactory $responseFactory
    )
    {
        $this->_request = $request;
        $this->copyQuote = $copyQuote;
        $this->_url = $url;
        $this->responseFactory = $responseFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->_request->getParam('id');
        if (!$id) {
            $resultRedirect->setPath('/');
            return $resultRedirect;
        }
        $quote = $this->copyQuote->copy($id);
        if ($quote) {
            $resultRedirect->setPath('checkout/cart');
            return $resultRedirect;
        }
        $resultRedirect->setPath('/');
        return $resultRedirect;
    }
}
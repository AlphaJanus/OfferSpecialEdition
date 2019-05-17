<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-04-03
 * Time: 16:54
 */

namespace Netzexpert\OfferSpecialEdition\Controller\Approval;

use \Netzexpert\OfferSpecialEdition\Model\SetQuote;

class Proceed extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var SetQuote
     */
    private $setQuote;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        SetQuote $setQuote,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\ResponseFactory $responseFactory
    )
    {
        $this->request = $request;
        $this->setQuote = $setQuote;
        $this->_url = $url;
        $this->responseFactory = $responseFactory;
        parent::__construct($context);
    }

    public function execute()
    {$resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->request->getParam('id');
        if (!$id) {
            $resultRedirect->setPath('/');
            return $resultRedirect;
        }
        $quote = $this->setQuote->setQuote($id);
        if ($quote) {
            $resultRedirect->setPath('checkout/cart');
            return $resultRedirect;
        }
        $resultRedirect->setPath('/');
        return $resultRedirect;
    }
}

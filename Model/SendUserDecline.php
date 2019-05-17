<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-04-04
 * Time: 18:02
 */

namespace Netzexpert\OfferSpecialEdition\Model;

use Magento\Framework\App\Action\Context;

class SendUserDecline extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $session;

    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    private $quoteRepository;

    /**
     * SendCartMyAccount constructor.
     * @param Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $urlInterface
     */
    public function __construct(
    Context $context,
    \Magento\Framework\App\Request\Http $request,
    \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\UrlInterface $urlInterface,
    \Magento\Checkout\Model\Session $session,
    \Magento\Quote\Model\QuoteRepository $quoteRepository

) {
    $this->request = $request;
    $this->transportBuilder = $transportBuilder;
    $this->storeManager = $storeManager;
    $this->_url = $urlInterface;
    $this->session = $session;
    $this->quoteRepository = $quoteRepository;
    parent::__construct($context);
}

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
{
    $store = $this->storeManager->getStore()->getId();
    $param = $this->request->getParam('offer_id');
    $quote = $this->quoteRepository->get($param);
    $userEmail = $quote->getCustomerEmail();
    $name = $quote->getCustomerFirstname();
    $link = $this->_url->getUrl('/');
    $transport = $this->transportBuilder->setTemplateIdentifier('user_decline_email')
        ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
        ->setTemplateVars(
            [
                'store'        => $this->storeManager->getStore(),
                'quote'        => $quote,
                'link'         => $link,
                'name'         => $name
            ]
        )
        ->setFrom('general')
        ->addTo($userEmail)
        ->getTransport();
    $transport->sendMessage();
}
}
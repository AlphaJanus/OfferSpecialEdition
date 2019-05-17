<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-04-03
 * Time: 11:59
 */

namespace Netzexpert\OfferSpecialEdition\Model;

use Magento\Framework\App\Action\Context;

class SendUserConfirmation extends \Magento\Framework\App\Action\Action
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
     * @var \Magento\Quote\Model\QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $session;

    /**
     * SendCartMyAccount constructor.
     * @param Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Magento\Checkout\Model\Session $session

    ) {
        $this->request = $request;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->_url = $urlInterface;
        $this->quoteRepository = $quoteRepository;
        $this->session = $session;
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
        try {
            $quote = $this->quoteRepository->get($param);
        } catch (\Exception $exception) {
            $this->messageManager->getMessages();
        }
        $name = $quote->getCustomer()->getFirstname();
        $userEmail = $quote->getCustomerEmail();
        $link = $this->_url->getUrl('offer/approval/proceed', ['id' => $param]);
        $transport = $this->transportBuilder->setTemplateIdentifier('user_confirmation_email')
            ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
            ->setTemplateVars(
                [
                    'store'        => $this->storeManager->getStore(),
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
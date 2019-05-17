<?php

namespace Netzexpert\OfferSpecialEdition\Controller\Quote;

use Magento\Framework\App\Action\Context;
use \Netzexpert\OfferSpecialEdition\Model\OfferRepository;

class ConfirmButtonMyAccount extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $session;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var \Netzexpert\OfferSpecialEdition\Model\SendUserConfirmation
     */
    private $sendUserConfirmation;

    /**
     * @var OfferRepository
     */
    private $offerRepository;

    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    private $responseFactory;

    /**
     * @var \Netzexpert\OfferSpecialEdition\Model\Offer
     */
    private $offer;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;


    /**
     * Confirm constructor.
     * @param \Magento\Checkout\Model\Session $session
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
     * @param Context $context
     */
    public function __construct(
        \Magento\Checkout\Model\Session $session,
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        \Netzexpert\OfferSpecialEdition\Model\SendUserConfirmation $sendUserConfirmation,
        \Netzexpert\OfferSpecialEdition\Model\OfferRepository $offerRepository,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Netzexpert\OfferSpecialEdition\Model\Offer $offer,
        \Magento\Customer\Model\Session $customerSession,
        Context $context
    )
    {
        $this->session = $session;
        $this->quoteRepository = $quoteRepository;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        $this->sendUserConfirmation = $sendUserConfirmation;
        $this->offerRepository = $offerRepository;
        $this->responseFactory = $responseFactory;
        $this->offer = $offer;
        $this->customerSession = $customerSession;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->redirectFactory->create();
        $test = $this->request->getParams();
        $param = $this->request->getParam('offer_id');
        try {
            $quote = $this->offerRepository->get($param);
        } catch (\Exception $exception) {
            $this->messageManager->getMessages();
        }
        if ($quote->getData('is_order') == null) {
            $quote->setData('is_order', '1');
            $this->offerRepository->save($quote);
            try {
                $this->sendUserConfirmation->execute();
            } catch (\Exception $exception) {
                $this->messageManager->getMessages();
            }
            $this->messageManager->addSuccessMessage('You have successfully granted user permissions for proceeding checkout!');
            $resultRedirect->setPath('/');
            $this->userApprove();
            return $resultRedirect;
        }
        $this->messageManager->addErrorMessage('User already has permissions for proceeding checkout.');
        $resultRedirect->setPath('/');
        return $resultRedirect;
    }

    /**
     * Write user who has approved to the table 'offer_quote'
     */
    public function userApprove()
    {
        $currentCustomer = $this->customerSession->getCustomer()->getId();
        $param = $this->request->getParam('offer_id');
        $quote = $this->offerRepository->get($param);
        if ($quote->getData('approved_by_user_id') == null) {
            $quote->setData('approved_by_user_id', $currentCustomer);
            try {
                $this->offerRepository->save($quote);
            } catch (\Exception $exception) {
                $this->messageManager->getMessages();
            }
        }
    }
}

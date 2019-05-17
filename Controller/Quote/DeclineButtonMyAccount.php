<?php

namespace Netzexpert\OfferSpecialEdition\Controller\Quote;

use Magento\Framework\App\Action\Context;

class DeclineButtonMyAccount extends \Magento\Framework\App\Action\Action
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
     * @var \Netzexpert\OfferSpecialEdition\Model\SendUserDecline
     */
    private $sendUserDecline;

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
        \Netzexpert\OfferSpecialEdition\Model\SendUserDecline $sendUserDecline,
        Context $context
    ) {
        $this->session = $session;
        $this->quoteRepository = $quoteRepository;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        $this->sendUserDecline = $sendUserDecline;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->redirectFactory->create();
        try {
            $this->sendUserDecline->execute();
        } catch (\Exception $exception) {
            $this->messageManager->getMessages();
        }
        $this->messageManager->addSuccessMessage('You have successfully declined user permissions for proceeding checkout!');
        $resultRedirect->setPath('/');
        return $resultRedirect;
    }
}

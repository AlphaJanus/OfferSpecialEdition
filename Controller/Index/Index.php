<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-01-29
 * Time: 10:56
 */

namespace Netzexpert\OfferSpecialEdition\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var \Magento\Framework\View\Element\UiComponentFactory
     */
    private $factory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\View\Element\UiComponentFactory $factory,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->pageFactory = $pageFactory;
        $this->factory = $factory;
        $this->customerSession = $customerSession;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        if ($this->customerSession->isLoggedIn()) {
            $isAjax = $this->getRequest()->isAjax();
            if ($isAjax) {
                $component = $this->factory->create($this->_request->getParam('namespace'));
                $this->prepareComponent($component);
                $this->_response->appendBody((string)$component->render());
            } else {
                $resultPage = $this->pageFactory->create();
                return $resultPage;
            }
        } else {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
    }
}

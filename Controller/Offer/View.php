<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-02-19
 * Time: 16:20
 */

namespace Netzexpert\OfferSpecialEdition\Controller\Offer;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;

class View extends Action
{
    private $customerSession;

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Customer\Model\Session $session,
        Context $context,
        PageFactory $pageFactory
    )
    {
        $this->customerSession = $session;
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        return $this->pageFactory->create();
    }
}

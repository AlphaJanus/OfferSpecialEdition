<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-03-07
 * Time: 11:25
 */

namespace Netzexpert\OfferSpecialEdition\Block\Checkout\Cart;

use Magento\Email\Model\Template\Config;
use Magento\Framework\View\Element\Template;

class FormSendMyAccount extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Config
     */
    private $emailConfig;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    private $groupRepository;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Netzexpert\OfferSpecialEdition\Model\OfferRepository
     */
    private $offerRepository;

    /**
     * Form constructor.
     * @param \Magento\Customer\Model\Session $session
     * @param Template\Context $context
     * @param Config $config
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Customer\Model\Session $session,
        Template\Context $context,
        Config $config,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\App\RequestInterface $request,
        \Netzexpert\OfferSpecialEdition\Model\OfferRepository $offerRepository,
        array $data = []
    )
    {
        $this->customerSession = $session;
        $this->groupRepository = $groupRepository;
        $this->offerRepository = $offerRepository;
        $this->_request = $request;
        parent::__construct($context, $data);
        $this->emailConfig = $config;
    }

    public function getAvailableTemplates() {
        $templates = $this->emailConfig->getAvailableTemplates();
        $module = 'Netzexpert_OfferSpecialEdition';
        $email1 = 'offer_email_template1';
        $email2 = 'offer_email_template2';
        return array_filter($templates, function ($var) use ($module, $email1, $email2) {
            return (($var['group'] == $module && $var['value'] == $email1) ||
                ($var['group'] == $module && $var['value'] == $email2));
        });
    }

    public function getFormUrl()
    {
        return $this->getUrl('offer/quote/sendcartmyaccount');
    }

    /**
     * Get Confirmation Button URL
     * @return string
     */
    public function getButtonConfirmUrl()
    {
        return $this->getUrl('offer/quote/confirmbuttonmyaccount');
    }

    /**+
     * Get Decline Button URL
     * @return string
     */
    public function getButtonDeclineUrl()
    {
        return $this->getUrl('offer/quote/declinebuttonmyaccount');
    }

    protected function _toHtml()
    {
        $inGroup = null;
        if ($this->customerSession->isLoggedIn()) {
            $customer = $this->customerSession->getCustomer()->getGroupId();
            $inGroup = $this->groupRepository->getById($customer)->getCode();
        }
        if ($inGroup !== 'Mitarbeiter' && $inGroup !== 'Anfragen') {
            return '';
        }
        return parent::_toHtml();
    }

    public function getOfferId()
    {
        $currentEntityId = $this->_request->getParam('entity_id');
        return $currentEntityId;
    }

    public function checkButtonsCustomer()
    {
        $customer = $this->customerSession->getCustomer()->getGroupId();
        $inGroup = $this->groupRepository->getById($customer)->getCode();

        if ($inGroup == 'Anfragen') {
            return true;
        }
        return false;
    }

    public function getQuoteEmail()
    {
        $params = $this->_request->getParams();
        $quote = $this->offerRepository->getById($params['entity_id']);
        return $quote->getData('email');
    }
}

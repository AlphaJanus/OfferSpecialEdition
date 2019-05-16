<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-01-16
 * Time: 15:16
 */

namespace Netzexpert\OfferSpecialEdition\Block\Checkout\Cart;

use Magento\Email\Model\Template\Config;
use Magento\Framework\View\Element\Template;

class Form extends \Magento\Framework\View\Element\Template
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
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Form constructor.
     * @param \Magento\Customer\Model\Session $session
     * @param Template\Context $context
     * @param Config $config
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        Template\Context $context,
        Config $config,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = [])
    {
        $this->customerSession = $customerSession;
        $this->groupRepository = $groupRepository;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
        $this->emailConfig = $config;
    }

    public function getAvailableTemplates()
    {
        $templates = $this->emailConfig->getAvailableTemplates();
        $module = 'Netzexpert_OfferSpecialEdition';
            return array_filter($templates, function ($var) use ($module) {
                return ($var['group'] == $module);
            });
    }

    public function getTemplateNonRegisteredUser()
    {
        $templates = $this->emailConfig->getAvailableTemplates();
        $module = 'Netzexpert_OfferSpecialEdition';
        return array_filter($templates, function ($var) use ($module) {
            return ($var['label'] == 'Email Cart Template');
        });
    }

    public function getFormUrl()
    {
        return $this->getUrl('offer/quote/send');
    }

    public function getStoreEmail()
    {
        return $this->_scopeConfig->getValue(
            'trans_email/ident_sales/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function checkCustomer()
    {
        $customer = $this->customerSession->getCustomer()->getGroupId();
        $inGroup = $this->groupRepository->getById($customer)->getCode();

        if ($inGroup == 'Mitarbeiter') {
            return true;
        }
        return false;
    }
}
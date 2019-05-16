<?php

namespace Netzexpert\OfferSpecialEdition\Setup;

use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Api\Data\GroupInterfaceFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Tax\Helper\Data as TaxHelper;
use Magento\TestFramework\Inspection\Exception;
use Psr\Log\LoggerInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var GroupInterfaceFactory
     */
    private $groupFactory;

    /**
     * @var GroupRepositoryInterface
     */
    private $groupRepository;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * InstallData constructor.
     * @param GroupInterfaceFactory $groupFactory
     * @param GroupRepositoryInterface $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param TaxHelper $taxHelper
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        GroupInterfaceFactory $groupFactory,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->groupFactory = $groupFactory;
        $this->groupRepository = $groupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->logger      = $logger;
    }

    /**
     * Install data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $searchCriteria = $this->searchCriteriaBuilder->addFilter('customer_group_code', 'Mitarbeiter')
            ->create();
        $searchCriteriaGroup = $this->searchCriteriaBuilder->addFilter('customer_group_code', 'Anfragen')
            ->create();
        try {
            $groupList = $this->groupRepository->getList($searchCriteria);
        } catch (\Exception $e) {
            $this->logger->alert('Could not get list of groups');
        }
        if (!$groupList->getTotalCount()) {
            $group = $this->groupFactory->create();
            $group->setCode('Mitarbeiter')
                ->setTaxClassId(
                    $this->scopeConfig->getValue(
                        TaxHelper::CONFIG_DEFAULT_CUSTOMER_TAX_CLASS,
                        ScopeInterface::SCOPE_STORE
                    )
                );
            try {
                $this->groupRepository->save($group);
            } catch (\Exception $e) {
                $this->logger->alert('Could not save group');
            }
        }
        try {
            $groupList = $this->groupRepository->getList($searchCriteriaGroup);
        } catch (\Exception $e) {
            $this->logger->alert('Could not get list of groups');
        }
        if (!$groupList->getTotalCount()) {
            $group = $this->groupFactory->create();
            $group->setCode('Anfragen')
                ->setTaxClassId(
                    $this->scopeConfig->getValue(
                        TaxHelper::CONFIG_DEFAULT_CUSTOMER_TAX_CLASS,
                        ScopeInterface::SCOPE_STORE
                    )
                );
            try {
                $this->groupRepository->save($group);
            } catch (\Exception $e) {
                $this->logger->alert('Could not save group');
            }
        }
    }
}
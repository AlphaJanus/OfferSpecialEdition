<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-05-14
 * Time: 15:35
 */

namespace Netzexpert\OfferSpecialEdition\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class ApprovedByUser extends \Magento\Ui\Component\Listing\Columns\Column
{

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepositoryInterface;

    /**
     * ApprovedByUser constructor.
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = [])
    {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                $customerId = $item['approved_by_user_id'];
                if ($customerId != null) {
                    $item[$name] = $this->customerRepositoryInterface->getById($customerId)->getEmail();
                } else {
                    $item[$name] = '';
                }
            }
        }
        return $dataSource;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-02-18
 * Time: 15:52
 */

namespace Netzexpert\OfferSpecialEdition\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Checkout\Model\Session;

class SavedOffersActions extends Column
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Quote\Model\Quote
     */
    private $quote;

    /**
     * @var Session
     */
    private $session;

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * SavedOffersActions constructor.
     * @param Session $session
     * @param \Magento\Quote\Model\Quote $quote
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        Session $session,
        \Magento\Quote\Model\Quote $quote,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Customer\Model\Session $customerSession,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->customerSession = $customerSession;
        $this->quote = $quote;
        $this->session = $session;
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['entity_id'])) {
                    $viewUrlPath = $this->getData('config/viewUrlPath') ?: '#';
                    $urlEntityParamName = $this->getData('config/urlEntityParamName') ?: 'entity_id';
                    $item[$name]['view'] = [
                        'href' => $this->urlBuilder->getUrl(
                            $viewUrlPath,
                            [
                                $urlEntityParamName => $item['entity_id']
                            ]
                        ),
                        'label' => __('View')
                    ];
                }
            }
        }
        return $dataSource;
    }
}
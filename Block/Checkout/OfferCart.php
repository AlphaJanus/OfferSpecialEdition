<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-02-19
 * Time: 17:51
 */

namespace Netzexpert\OfferSpecialEdition\Block\Checkout;


class OfferCart extends \Magento\Checkout\Block\Cart
{
    /**
     * Config settings path to determine when pager on checkout/cart/index will be visible
     */
    const XPATH_CONFIG_NUMBER_ITEMS_TO_DISPLAY_PAGER = 'checkout/cart/number_items_to_display_pager';

    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote\Item\Collection
     */
    private $itemsCollection;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quoteFactory;

    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory
     *
     */
    private $itemCollectionFactory;

    /**
     * @var \Netzexpert\Offer\Model\OfferRepository
     */
    private $offerRepository;

    /**
     * @var \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface
     */
    private $joinAttributeProcessor;

    /**
     * Is display pager on shopping cart page
     *
     * @var bool
     */
    private $isPagerDisplayed;

    /**
     * @var \Magento\Framework\DataObject
     */
    private $dataObject;


    /**
     * OfferCart constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Model\ResourceModel\Url $catalogUrlBuilder
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory $itemCollectionFactory
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $joinProcessor
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Netzexpert\Offer\Model\OfferRepository $offerRepository
     * @param \Magento\Quote\Model\QuoteFactory $quoteFactory
     * @param \Magento\Framework\DataObject $dataObject
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrlBuilder,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $joinProcessor,
        \Magento\Framework\App\RequestInterface $request,
        \Netzexpert\OfferSpecialEdition\Model\OfferRepository $offerRepository,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Framework\DataObject $dataObject,
        array $data = []
    ) {
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->joinAttributeProcessor = $joinProcessor;
        $this->_request = $request;
        $this->offerRepository = $offerRepository;
        $this->quoteFactory = $quoteFactory;
        $this->dataObject = $dataObject;
        parent::__construct(
            $context,
            $customerSession,
            $checkoutSession,
            $catalogUrlBuilder,
            $cartHelper,
            $httpContext,
            $data
        );
    }

    /**
     * Prepare Quote Item Product URLs
     * When we don't have custom_items, items URLs will be collected for Collection limited by pager
     * Pager limit on checkout/cart/index is determined by configuration
     * Configuration path is Store->Configuration->Sales->Checkout->Shopping Cart->Number of items to display pager
     *
     * @return void
     * @since 100.2.0
     */
    protected function _construct()
    {
        if (!$this->isPagerDisplayedOnPage()) {
            parent::_construct();
        }
        if ($this->hasData('template')) {
            $this->setTemplate($this->getData('template'));
        }
    }

    /**
     * {@inheritdoc}
     * @since 100.2.0
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
            $availableLimit = (int)$this->_scopeConfig->getValue(
                self::XPATH_CONFIG_NUMBER_ITEMS_TO_DISPLAY_PAGER,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $itemsCollection = $this->getItemsForGrid();
            /** @var  \Magento\Theme\Block\Html\Pager $pager */
            $pager = $this->getLayout()->createBlock(\Magento\Theme\Block\Html\Pager::class);
            $pager->setAvailableLimit([$availableLimit => $availableLimit])->setCollection($itemsCollection);
            $this->setChild('pager', $pager);
            $itemsCollection->load();
            $this->prepareItemUrls();
        return $this;
    }

    /**
     * Prepare quote items collection for pager
     *
     * @return \Magento\Quote\Model\ResourceModel\Quote\Item\Collection
     * @since 100.2.0
     */
    public function getItemsForGrid()
    {
        if (!$this->itemsCollection) {
            $currentUrl = $this->_request->getParams();
            $currentOffer = $this->offerRepository->get($currentUrl['entity_id']);
            $quoteId = $currentOffer->getData('quote_id');
            $currentQuote = $this->quoteFactory->create()->load($quoteId);
            /** @var \Magento\Quote\Model\ResourceModel\Quote\Item\Collection $itemCollection */
            $itemCollection = $this->itemCollectionFactory->create();
            $itemCollection->setQuote($currentQuote);
            $itemCollection->addFieldToFilter('parent_item_id', ['null' => true]);
            $itemCollection->getData();
            $this->joinAttributeProcessor->process($itemCollection);

            $this->itemsCollection = $itemCollection;
        }
        return $this->itemsCollection;
    }

    /**
     * {@inheritdoc}
     * @since 100.2.0
     */
    public function getItems()
    {
        return $this->getItemsForGrid()->getItems();
    }

    /**
     * Verify if display pager on shopping cart
     * If cart block has custom_items and items qty in the shopping cart<limit from stores configuration
     *
     * @return bool
     */
    private function isPagerDisplayedOnPage()
    {
        if (!$this->isPagerDisplayed) {
            $availableLimit = (int)$this->_scopeConfig->getValue(
                self::XPATH_CONFIG_NUMBER_ITEMS_TO_DISPLAY_PAGER,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $this->isPagerDisplayed = !$this->getCustomItems() && $availableLimit < $this->getItemsCount();
        }
        return $this->isPagerDisplayed;
    }
}

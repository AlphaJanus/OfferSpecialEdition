<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-01-18
 * Time: 11:04
 */

namespace Netzexpert\OfferSpecialEdition\Controller\Quote;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Checkout\Model\Cart;
use \Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\QuoteRepository;
use Netzexpert\OfferSpecialEdition\Model\OfferFactory;
use Netzexpert\OfferSpecialEdition\Model\OfferItemFactory;
use Netzexpert\OfferSpecialEdition\Model\OfferRepository;
use Netzexpert\OfferSpecialEdition\Model\OfferItemRepository;

class Send extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /** @var CheckoutSession  */
    private $checkoutSession;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var \Netzexpert\OfferSpecialEdition\Model\OfferRepository $offerRepository
     */
    private $offerRepository;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var OfferFactory
     */
    private $offerFactory;

    /**
     * @var OfferItemFactory
     */
    private $offerItemFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $date;

    /**
     * @var \Netzexpert\OfferSpecialEdition\Model\OfferItemRepository OfferItemRepository
     */
    private $offerItemRepository;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /** @var \Magento\Quote\Model\QuoteFactory  */
    private $quote;

    /**
     * @var \Magento\Framework\Message\Manager
     */
    private $message;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    private $groupRepository;

    /**
     * @var null
     */
    private $requestData = null;

    /**
     * Send constructor.
     * @param Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param CheckoutSession $session
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param OfferRepository $offerRepository
     * @param OfferFactory $offerFactory
     * @param OfferItemFactory $offerItemFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param OfferItemRepository $offerItemRepository
     * @param \Magento\Customer\Model\Session $customerSession
     * @param Cart $cart
     * @param \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
     * @param \Magento\Quote\Model\QuoteFactory $quote
     * @param ProductRepository $productRepository
     * @param \Magento\Framework\Message\Manager $message
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Request\Http $request,
        CheckoutSession $session,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        OfferRepository $offerRepository,
        OfferFactory $offerFactory,
        OfferItemFactory $offerItemFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        OfferItemRepository $offerItemRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        \Magento\Quote\Model\QuoteFactory $quote,
        ProductRepository $productRepository,
        \Magento\Framework\Message\Manager $message,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
    ) {
        $this->request = $request;
        $this->checkoutSession  = $session;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->offerRepository = $offerRepository;
        $this->offerFactory = $offerFactory;
        $this->offerItemFactory = $offerItemFactory;
        $this->date = $date;
        $this->offerItemRepository = $offerItemRepository;
        $this->customerSession = $customerSession;
        $this->cart = $cart;
        $this->redirectFactory = $redirectFactory;
        $this->quote = $quote;
        $this->productRepository = $productRepository;
        $this->message = $message;
        $this->scopeConfig = $scopeConfig;
        $this->groupRepository = $groupRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $data = $this->request->getParams();
        $this->requestData = $data;
        $store = $this->storeManager->getStore()->getId();
        $quote = $this->checkoutSession->getQuote();
        $adminUser = $this->customerSession->getCustomer()->getData();
        $name = (key_exists('firstname', $adminUser) && key_exists('lastname', $adminUser)) ? $adminUser['firstname'].' '. $adminUser['lastname'] : '';
        $link = $this->_url->getUrl('offer/quote/duplicate', ['id' => $quote->getId()]);
        $feedback = $this->_url->getUrl('contact/index/index');
        $this->saveQuote($quote);
        $transport = $this->transportBuilder->setTemplateIdentifier($data['template'])
            ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
            ->setTemplateVars(
                [
                    'store'        => $this->storeManager->getStore(),
                    'quote'        => $quote,
                    'link'         => $link,
                    'feedback'     => $feedback,
                    'name'         => $data['name'],
                    'comment'      => $data['comment'],
                    'adminUser'    => $name,
                    'userEmail'    => $this->checkUserEmail()
                ]
            )
            ->setFrom('general')
            ->addTo($this->checkCustomer())
            ->getTransport();
        $this->saveQuote($quote);
        $this->redirectQuote();
        $transport->sendMessage();
        $this->messageManager->addSuccessMessage('Ihre Anfrage wurde gesendet. Wir werden ihnen in Kürze auf Ihre Anfrage antworten.');
        $resultRedirect = $this->redirectFactory->create();
        $resultRedirect->setPath('/');
        return $resultRedirect;
    }

    /**
     * @return mixed
     */
    public function getStoreEmail()
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_sales/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $quote
     * @return bool|\Netzexpert\OfferSpecialEdition\Model\Offer|null
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\TemporaryState\CouldNotSaveException
     */
    private function saveQuote($quote) {
        $quote = $this->checkoutSession->getQuote();
        $id = $quote['entity_id'];
        $dataEmail = $this->request->getParams();
        $offerQuote = $this->offerRepository->getByQuoteId($id);
        if (!$offerQuote) {
            $offerQuote = $this->offerFactory->create();
        }
        $offerQuote->setQuoteId($quote->getId())->setDate($this->date->gmtDate())->setEmail($dataEmail['email'])
            ->setCustomerId($this->customerSession->getCustomer()->getId());
        $this->offerRepository->save($offerQuote);
        /** @var Item $item */
        foreach ($quote->getAllItems() as $item) {
            $offerItem = $this->offerItemFactory->create();
            $offerItem->setOfferId($offerQuote->getId())->setQuoteItemId($item->getItemId());
            $this->offerItemRepository->save($offerItem);
        }
        return $offerQuote;
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function redirectQuote()
    {
//        if ($this->customerSession->isLoggedIn() == false) {
            $currentQuote = $this->checkoutSession->getQuote();
            $offerQuote = $this->offerRepository->getByQuoteId($currentQuote->getData('entity_id'));
            $currentQuote->setData('offer_id', $offerQuote->getData('entity_id'));
            $currentQuote->save();
            $this->checkoutSession->setQuoteId(null);
            $customer = $currentQuote->getCustomer();
            $store = $this->storeManager->getStore();
            $quoteItems = $currentQuote->getItems();
//            $offerQuote = $this->offerRepository->getByQuoteId($currentQuote->getData('entity_id'));
            if (empty($quoteItems)) {
                return;
            }
            $newQuote = $this->quote->create();
            $newQuote->setData($currentQuote->getData());
//            $newQuote->setData('offer_id', $offerQuote->getData('entity_id'));
            $newQuote->setId(null);
            $newQuote->assignCustomer($customer);
            $newQuote->setStore($store);
            $newQuote->setCurrency();
            $newQuote->getBillingAddress();
            $newQuote->getShippingAddress()->setCollectShippingRates(true);
            $newQuote->setIsActive(true);
            try {
                $newQuote->save();
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }

            /** @var \Magento\Quote\Api\Data\CartItemInterface | Item $item */
            foreach ($quoteItems as $item) {
                $itemSku = $item->getSku();
                $data = $item->getData();
                unset($data['item_id']);
                unset($data['quote_id']);
                try {
                    $request = $item->getBuyRequest();
                    $productId = $request->getData('product');
                    $product = $this->productRepository->getById($productId);
//                    $product = $this->productRepository->get($productId);
                    $newItem = $newQuote->addProduct($product, $request);
                    $newItem->setData($data);
                    $newItem->calcRowTotal()->save();
                } catch (NoSuchEntityException $exception) {
                    $this->messageManager->addErrorMessage($exception->getMessage());
                } catch (LocalizedException $exception) {
                    $this->messageManager->addErrorMessage($exception->getMessage());
                }
            }
            $newQuote->collectTotals();
            try {
                $newQuote->save();
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }
            $this->checkoutSession->replaceQuote($newQuote);
        }
    /**
     * Check if customer in Mitarbeiter group or unregistered/simple user
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function checkCustomer()
    {
        $customer = $this->customerSession->getCustomer()->getGroupId();
        $inGroup = $this->groupRepository->getById($customer)->getCode();

        if ($inGroup == 'Mitarbeiter') {
            return $this->requestData['email'];
        }
        return $email = $this->getStoreEmail();
    }

    /**
     * Get user email if unregistered/simple user
     * @return bool|mixed
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function checkUserEmail()
    {
        $customer = $this->customerSession->getCustomer()->getGroupId();
        $inGroup = $this->groupRepository->getById($customer)->getCode();

        if ($inGroup == 'Mitarbeiter') {
            return false;
        }
        return $this->requestData['email'];
    }
//    }
}

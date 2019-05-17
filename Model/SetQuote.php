<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-04-03
 * Time: 16:58
 */

namespace Netzexpert\OfferSpecialEdition\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\Manager;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository;
use \Magento\Checkout\Model\Session as CheckoutSession;
use \Netzexpert\OfferSpecialEdition\Model\OfferRepository;

class SetQuote
{
    private $checkoutSession;

    private $quoteRepository;

    private $messageManager;

    private $session;

    private $offerRepository;

    /**
     * CopyOffer constructor.
     * @param CheckoutSession $checkoutSession
     * @param QuoteRepository $quoteRepository
     * @param Manager $manager
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        QuoteRepository $quoteRepository,
        Manager $manager,
        \Magento\Customer\Model\Session $session,
        \Netzexpert\OfferSpecialEdition\Model\OfferRepository $offerRepository
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->messageManager = $manager;
        $this->session = $session;
        $this->offerRepository = $offerRepository;
    }

    /**
     * @param $id
     * @return bool
     */
    public function setQuote($id)
    {
        if($id > 0)
        {
            $offerQuote = $this->offerRepository->getById($id)->getQuoteId();
            try {
                $originalQuote = $this->quoteRepository->get($offerQuote);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addError(__($exception->getMessage()));
                return false;
            }
            $quoteCustomerEmail = $originalQuote->getCustomer()->getEmail();
            $sessionCustomerEmail = $this->session->getCustomer()->getEmail();
            if (
                $quoteCustomerEmail != $sessionCustomerEmail
            ) {
                return false;
            } else {
                $items = $originalQuote->getAllVisibleItems();
                $quote = $this->checkoutSession->getQuote();
                $quote->removeAllItems();

                /** @var Quote\Item $item */
                foreach ($items as $item)
                {
                    $_product = $item->getProduct();
                    $options = $_product->getTypeInstance()->getOrderOptions($item->getProduct());
                    $info = $options['info_buyRequest'];
                    $request1 = new \Magento\Framework\DataObject();
                    $request1->setData($info);
                    try {
                        $quote->addProduct($_product, $request1);
                    } catch (LocalizedException $exception) {
                        $this->messageManager->addError($exception->getMessage());
                    }
                }
                try {
                    $quote->getShippingAddress()->setCollectShippingRates(true);
                    $this->quoteRepository->save($quote);
                    $quote->collectTotals();
                    $this->checkoutSession->replaceQuote($quote);
                    return true;
                } catch (\Exception $e)
                {
                    $this->messageManager->addError( __($e->getMessage()) );
                }
            }
        }
        return false;
    }
}
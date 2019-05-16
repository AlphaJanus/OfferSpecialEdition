<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-01-10
 * Time: 17:03
 */

namespace Netzexpert\OfferSpecialEdition\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\Manager;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository;
use \Magento\Checkout\Model\Session as CheckoutSession;

class CopyOffer
{
    private $checkoutSession;

    private $quoteRepository;

    private $messageManager;

    /**
     * CopyOffer constructor.
     * @param CheckoutSession $checkoutSession
     * @param QuoteRepository $quoteRepository
     * @param Manager $manager
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        QuoteRepository $quoteRepository,
        Manager $manager
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->messageManager = $manager;
    }

    /**
     * @param $id
     * @return bool
     */
    public function copy($id)
    {
         if($id > 0)
         {
             try {
             $originalQuote = $this->quoteRepository->get($id);
             } catch (NoSuchEntityException $exception) {
                 $this->messageManager->addError(__($exception->getMessage()));
                 return false;
             }
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
         return false;
    }
}
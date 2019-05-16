<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-03-15
 * Time: 17:04
 */

namespace Netzexpert\OfferSpecialEdition\Plugin\Cart;

use Psr\Log\LoggerInterface;

class ConfigurePlugin
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var \Netzexpert\OfferSpecialEdition\Model\OfferItemRepository
     */
    private $offerItemRepository;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $session;

    /**
     * @var \Netzexpert\OfferSpecialEdition\Model\OfferRepository
     */
    private $offerRepository;

    /**
     * ConfigurePlugin constructor.
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Netzexpert\OfferSpecialEdition\Model\OfferItemRepository $offerItemRepository,
        LoggerInterface $logger,
        \Magento\Checkout\Model\Session $session,
        \Netzexpert\OfferSpecialEdition\Model\OfferRepository $offerRepository
    ) {
        $this->request = $request;
        $this->quoteRepository = $quoteRepository;
        $this->offerItemRepository = $offerItemRepository;
        $this->logger = $logger;
        $this->session = $session;
        $this->offerRepository = $offerRepository;
    }

    /**
     * @param \Magento\Checkout\Controller\Cart\Configure $configure
     */
    public function beforeExecute(
        \Magento\Checkout\Controller\Cart\Configure $configure
    )
    {
        $urlParam = $this->request->getParam('is_offer');
        if ($urlParam == true) {
            $param = $this->request->getParams();
            $quoteItem = $this->offerItemRepository->getById($param['id']);
            $quoteData = $quoteItem->getData();
            $offer = $this->offerRepository->getById($quoteData['offer_id']);
                try {
                    $quote = $this->quoteRepository->get($offer['quote_id']);
                    $quote->setIsActive(1);
                    $this->quoteRepository->save($quote);
                    $this->session->replaceQuote($quote);
                } catch (\Exception $exception) {
                    $this->logger->alert($exception->getMessage());
                }
        }
    }
}
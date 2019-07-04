<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-03-07
 * Time: 11:02
 */

namespace Netzexpert\OfferSpecialEdition\Controller\Quote;

use Magento\Framework\App\Action\Context;


class SendCartMyAccount extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var \Netzexpert\OfferSpecialEdition\Model\OfferRepository
     */
    private $offerRepository;

    /**
     * SendCartMyAccount constructor.
     * @param Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Netzexpert\OfferSpecialEdition\Model\OfferRepository $offerRepository
    ) {
        $this->request = $request;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->_url = $urlInterface;
        $this->quoteRepository = $quoteRepository;
        $this->offerRepository = $offerRepository;
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
        $store = $this->storeManager->getStore()->getId();
//        $offerQuote = $this->offerRepository->getByQuoteId($data['offer_id'])->getQuoteId();
        $quote = $this->quoteRepository->get($data['offer_id']);
        $link = $this->_url->getUrl('offer/quote/duplicate', ['id' => $quote->getId()]);
        $feedback = $this->_url->getUrl('contact/index/index');
        $transport = $this->transportBuilder->setTemplateIdentifier($data['template'])
            ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
            ->setTemplateVars(
                [
                    'store'        => $this->storeManager->getStore(),
                    'quote'        => $quote,
                    'link'         => $link,
                    'feedback'     => $feedback,
                    'name'         => $data['name'],
                    'comment'      => $data['comment']
                ]
            )
            ->setFrom('general')
            ->addTo($data['email'])
            ->getTransport();
        $transport->sendMessage();
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-02-06
 * Time: 10:40
 */

namespace Netzexpert\OfferSpecialEdition\Model;

use GuzzleHttp\Exception\ConnectException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Validation\ValidationException;
use \Netzexpert\OfferSpecialEdition\Api\Data\OfferInterface;

class OfferRepository implements \Netzexpert\OfferSpecialEdition\Api\OfferRepositoryInterface
{
    private $offerFactory;

    private $resourceModel;

    public function __construct(
        \Netzexpert\OfferSpecialEdition\Model\OfferFactory $offerFactory,
        \Netzexpert\OfferSpecialEdition\Model\ResourceModel\Offer $resourceModel
    )
    {
        $this->offerFactory = $offerFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * @param OfferInterface $offer
     * @return mixed|OfferInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\TemporaryState\CouldNotSaveException
     */
    public function save(OfferInterface $offer)
    {
        try {
            $this->resourceModel->save($offer);
        } catch (ConnectException $exception) {
            throw new \Magento\Framework\Exception\TemporaryState\CouldNotSaveException(
                __('Database connection error'),
                $exception,
                $exception->getCode()
            );
        } catch (DeadlockException $exception) {
            throw new \Magento\Framework\Exception\TemporaryState\CouldNotSaveException(
            __('Database deadlock found when trying to get lock'),
                $exception,
                $exception->getCode()
        );
        } catch (LockWaitException $exception) {
            throw new \Magento\Framework\Exception\TemporaryState\CouldNotSaveException(
                __('Database lock wait timeout exceeded'),
                $exception,
                $exception->getCode()
            );
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__('Unable to save offer'), $e);
        }
        return $offer;
    }

    /**
     * @param int $id
     * @return bool|mixed
     * @throws StateException
     */
    public function deleteById($id)
    {
        /** @var \Netzexpert\OfferSpecialEdition\Model\Offer $offer */
        $offer = $this->offerFactory->create();
        $offer->load($offer, $id);
        try {
            return $this->delete($offer);
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to delete offer', $offer->getTable($offer))
            );
        }
    }

    /**
     * @param OfferInterface $offer
     * @return bool|mixed
     * @throws StateException
     */
    public function delete(OfferInterface $offer)
    {
        try {
            $this->resourceModel->delete($offer);
            return true;
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __('Unable to remove reminder %1', $offer->getId())
            );
        }
    }

    /**
     * @param int $id
     * @return bool|Offer|null
     */
    public function get($id)
    {
        /** @var \Netzexpert\OfferSpecialEdition\Model\Offer $offer */
        $offer = $this->offerFactory->create();
        $offer->load($id);
        if (!$offer->getId()) {
            return null;
        }
        return $offer;
    }

    public function getById($id)
    {
        /** @var \Netzexpert\OfferSpecialEdition\Model\Offer $offer */
        $offer = $this->offerFactory->create();
        $offer->load($id);
        if (!$offer->getId()) {
            return null;
        }
        return $offer;
    }

    public function getApprovedUserId($approvedUserId)
    {
        /** @var \Netzexpert\OfferSpecialEdition\Model\Offer $offer */
        $offer = $this->offerFactory->create();
        $offer->load($approvedUserId);
        if (!$offer->getApprovedByUserId()) {
            return null;
        }
        return $offer;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed|void
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        // TODO: Implement getList() method.
    }

}
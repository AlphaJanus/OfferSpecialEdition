<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-02-05
 * Time: 17:51
 */

namespace Netzexpert\OfferSpecialEdition\Model;

use GuzzleHttp\Exception\ConnectException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Validation\ValidationException;
use \Netzexpert\OfferSpecialEdition\Api\Data\OfferItemInterface;

class OfferItemRepository implements \Netzexpert\OfferSpecialEdition\Api\OfferItemRepositoryInterface
{
    /**
     * @var OfferItemFactory
     */
    private $offerItemFactory;

    private $resourceModel;

    /**
     * OfferItemRepository constructor.
     * @param OfferItemFactory $offerItemFactory
     * @param ResourceModel\OfferItem $resourceModel
     */
    public function __construct(
        OfferItemFactory $offerItemFactory,
        \Netzexpert\OfferSpecialEdition\Model\ResourceModel\OfferItem $resourceModel
    )
    {
        $this->offerItemFactory = $offerItemFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * @param OfferItemInterface $offerItem
     * @return mixed
     * @throws CouldNotSaveException
     * @throws \Magento\Framework\Exception\TemporaryState\CouldNotSaveException
     */
    public function save(OfferItemInterface $offerItem)
    {
        try {
            $this->resourceModel->save($offerItem);
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
        return $offerItem;
    }

    /**
     * @param int $id
     * @return bool|mixed
     * @throws StateException
     */
    public function deleteById($id)
    {
        /** @var \Netzexpert\OfferSpecialEdition\Model\OfferItem $offerItem */
        $offer = $this->offerItemFactory->create();
        $offer->load($offerItem, $id);
        try {
            return $this->delete($offer);
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to delete offer', $offer->getTable($offer))
            );
        }
    }

    /**
     * @param OfferItemInterface $offerItem
     * @return bool|mixed
     * @throws StateException
     */
    public function delete(OfferItemInterface $offerItem)
    {
        try {
            $this->resourceModel->delete($offerItem);
            return true;
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __('Unable to remove reminder %1', $offerItem->getId())
            );
        }
    }

    /**
     * @param int $id
     * @return bool|OfferItem
     */
    public function getById($id)
    {
        /** @var \Netzexpert\Offer\Model\OfferItem $offer */
        $offer = $this->offerItemFactory->create();
        $offer->load($id, 'quote_item_id');
        if (!$offer->getId()) {
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

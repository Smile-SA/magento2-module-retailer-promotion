<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Model;

use Exception;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface as CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Smile\RetailerPromotion\Api\Data\PromotionInterface;
use Smile\RetailerPromotion\Api\Data\PromotionInterfaceFactory;
use Smile\RetailerPromotion\Api\Data\PromotionSearchResultsInterfaceFactory;
use Smile\RetailerPromotion\Api\PromotionRepositoryInterface;
use Smile\RetailerPromotion\Model\ResourceModel\Promotion as PromotionResourceModel;
use Smile\RetailerPromotion\Model\ResourceModel\Promotion\CollectionFactory as PromotionCollectionFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PromotionRepository implements PromotionRepositoryInterface
{
    /**
     * Item constructor.
     */
    public function __construct(
        protected PromotionInterfaceFactory $objectFactory,
        protected PromotionResourceModel $objectResource,
        protected PromotionSearchResultsInterfaceFactory $searchResultsFactory,
        protected CollectionProcessor $collectionProcessor,
        protected FilterBuilder $filterBuilder,
        protected SearchCriteriaBuilder $searchCriteriaBuilder,
        protected PromotionCollectionFactory $promotionCollectionFactory
    ) {
    }

    /**
     * @inheritdoc
     */
    public function get($promotionId)
    {
        /** @var \Smile\RetailerPromotion\Model\Promotion $object */
        $object = $this->objectFactory->create();
        $this->objectResource->load($object, $promotionId);

        if (!$object->getId()) {
            throw NoSuchEntityException::singleField('objectId', $promotionId);
        }

        return $object;
    }

    /**
     * @inheritdoc
     */
    public function getByRetailerId($retailerId)
    {
        $this->searchCriteriaBuilder
            ->addFilter(PromotionInterface::RETAILER_ID, $retailerId);

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResult = $this->getList($searchCriteria);

        $items = $searchResult->getItems();
        if (empty($items)) {
            throw NoSuchEntityException::singleField(PromotionInterface::TITLE, $retailerId);
        }

        return reset($items);
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Smile\RetailerPromotion\Model\ResourceModel\Promotion\Collection $collection */
        $collection = $this->promotionCollectionFactory->create();

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $this->collectionProcessor->process($searchCriteria, $collection);

        $collection->load();
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function save(PromotionInterface $promotion)
    {
        try {
            /** @var \Smile\RetailerPromotion\Model\Promotion $promotion */
            $this->objectResource->save($promotion);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $promotion;
    }

    /**
     * @inheritdoc
     */
    public function delete(PromotionInterface $promotion)
    {
        try {
            /** @var \Smile\RetailerPromotion\Model\Promotion $promotion */
            $this->objectResource->delete($promotion);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($promotionId)
    {
        return $this->delete($this->get($promotionId));
    }
}

<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Cron;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Smile\RetailerPromotion\Api\Data\PromotionInterface;
use Smile\RetailerPromotion\Api\PromotionRepositoryInterface;

/**
 * UpdateStatus cron for retailer promotion management.
 */
class UpdateStatus
{
    /**
     * UpdateStatus constructor.
     *
     * @param PromotionRepositoryInterface $promotionRepository   Repository.
     * @param SearchCriteriaBuilder        $searchCriteriaBuilder Search criteria.
     * @param DateTime                     $date                  Date.
     */
    public function __construct(
        protected PromotionRepositoryInterface $promotionRepository,
        protected SearchCriteriaBuilder $searchCriteriaBuilder,
        protected DateTime $date
    ) {
    }

    /**
     * Execute task
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): void
    {
        $date = $this->date->gmtDate();

        $this->searchCriteriaBuilder
            ->addFilter(PromotionInterface::END_AT, $date, 'lteq')
            ->addFilter(PromotionInterface::IS_ACTIVE, true);

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResult = $this->promotionRepository->getList($searchCriteria)->getItems();

        if (!empty($searchResult)) {
            foreach ($searchResult as $result) {
                $result->setIsActive(false);
                $this->promotionRepository->save($result);
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Plugin;

use DateTime;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Smile\RetailerPromotion\Api\Data\PromotionInterface;
use Smile\RetailerPromotion\Api\PromotionRepositoryInterface;
use Smile\StoreLocator\Block\View\Map;

class StoreLocatorBlockViewMapPlugin
{
    public function __construct(
        private PromotionRepositoryInterface $promotionRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder,
        private FilterBuilder $filterBuilder,
        private FilterGroupBuilder $filterGroupBuilder
    ) {
    }

    /**
     * Add promotion in markers.
     *
     * @param Map $block The block Map
     * @param array|null $result List of markers
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterGetMarkerData(Map $block, ?array $result): ?array
    {
        if (!empty($result)) {
            $promoList = $this->getPromoListByRetailerId((int) $result[0]['id']);
            $imageUrlPromotion = $block->getImageUrl() . '/retailerpromotion/';
            foreach ($promoList as $promo) {
                $image = $promo->getMediaPath() ? $imageUrlPromotion . $promo->getMediaPath() : false;
                $pdfFile = $promo->getPdf() ? $promo->getPdf() : false;
                $promotionLink = $promo->getLink() ? $promo->getLink() : false;

                $result[0]['promotion'][] =
                    [
                        'media' => $image,
                        'title' => $promo->getTitle(),
                        'description' => $promo->getDescription(),
                        'pdfFile' => $pdfFile,
                        'link' => $promotionLink,
                    ];
            }
        }
        return $result;
    }

    /**
     * Get the list of promotions.
     *
     * @return PromotionInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getPromoListByRetailerId(int $retailerId): array
    {
        $now = new DateTime();
        $currDateFormat = $now->format('Y-m-d');

        $retailerIdFilter = $this->filterBuilder
            ->setField(PromotionInterface::RETAILER_ID)
            ->setConditionType('in')
            ->setValue([0, $retailerId])
            ->create();
        $retailerStatus = $this->filterBuilder
            ->setField(PromotionInterface::STATUS)
            ->setConditionType('in')
            ->setValue([1, 2])
            ->create();
        $retailerActive = $this->filterBuilder
            ->setField(PromotionInterface::IS_ACTIVE)
            ->setValue('1')
            ->create();
        $retailerEndDate = $this->filterBuilder
            ->setField(PromotionInterface::END_AT)
            ->setConditionType('gteq')
            ->setValue($currDateFormat)
            ->create();
        $retailerCurrentDate = $this->filterBuilder
            ->setField(PromotionInterface::CREATED_AT)
            ->setConditionType('eq')
            ->setValue($currDateFormat)
            ->create();
        $retailerCurrentDateEnd = $this->filterBuilder
            ->setField(PromotionInterface::END_AT)
            ->setConditionType('eq')
            ->setValue($currDateFormat)
            ->create();
        $retailerStartDate = $this->filterBuilder
            ->setField(PromotionInterface::CREATED_AT)
            ->setConditionType('lteq')
            ->setValue($currDateFormat)
            ->create();
        $retailerEmptyEnd = $this->filterBuilder
            ->setField(PromotionInterface::END_AT)
            ->setConditionType('null')
            ->create();
        $retailerEmptyStart = $this->filterBuilder
            ->setField(PromotionInterface::CREATED_AT)
            ->setConditionType('null')
            ->create();
        /** @var \Magento\Framework\Api\Search\FilterGroup[] $filterGroup */
        $filterGroup = [
            $this->filterGroupBuilder
                ->addFilter($retailerIdFilter)
                ->create(),
            $this->filterGroupBuilder
                ->addFilter($retailerStatus)
                ->create(),
            $this->filterGroupBuilder
                ->addFilter($retailerActive)
                ->create(),
            $this->filterGroupBuilder
                ->addFilter($retailerStartDate)
                ->addFilter($retailerCurrentDate)
                ->addFilter($retailerEmptyStart)
                ->create(),
            $this->filterGroupBuilder
                ->addFilter($retailerEndDate)
                ->addFilter($retailerCurrentDateEnd)
                ->addFilter($retailerEmptyEnd)
                ->create(),
        ];
        $searchCriteria = $this->searchCriteriaBuilder
            ->setFilterGroups($filterGroup)
            ->create();
        $searchResult = $this->promotionRepository->getList($searchCriteria);

        $items = $searchResult->getItems();

        return $items;
    }
}

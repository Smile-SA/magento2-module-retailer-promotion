<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for promotion search results.
 *
 * @api
 */
interface PromotionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get promotions list.
     *
     * @return \Smile\RetailerPromotion\Api\Data\PromotionInterface[]
     */
    public function getItems();

    /**
     * Set promotions list.
     *
     * @param \Smile\RetailerPromotion\Api\Data\PromotionInterface[] $items The items
     * @return $this
     */
    public function setItems(array $items);
}

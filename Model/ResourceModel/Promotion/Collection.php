<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Model\ResourceModel\Promotion;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Smile\RetailerPromotion\Model\Promotion;

/**
 * Promotion Collection class
 */
class Collection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName) Method is inherited.
     */
    protected function _construct(): void
    {
        $this->_init(
            Promotion::class,
            \Smile\RetailerPromotion\Model\ResourceModel\Promotion::class
        );
    }
}

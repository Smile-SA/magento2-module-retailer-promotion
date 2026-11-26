<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Model\Status\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    /**#@+
     * Promotion's Statuses
     */
    public const STATUS_LOCAL = 1;
    public const STATUS_CENTRAL = 2;
    /**#@-*/

    /**
     * Get options
     */
    public function toOptionArray(): array
    {
        $availableOptions = $this->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }

    /**
     * Prepare promotion's statuses.
     */
    protected function getAvailableStatuses(): array
    {
        return [self::STATUS_LOCAL => __('Local'), self::STATUS_CENTRAL => __('Central')];
    }
}

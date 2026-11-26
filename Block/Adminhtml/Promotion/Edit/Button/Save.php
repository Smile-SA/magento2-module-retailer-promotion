<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Block\Adminhtml\Promotion\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Smile\RetailerPromotion\Block\Adminhtml\Promotion\Edit\AbstractButton;

class Save extends AbstractButton implements ButtonProviderInterface
{
    /**
     * Get the button data
     */
    public function getButtonData(): array
    {
        return [
            'label'          => __('Save Promotion'),
            'class'          => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order'     => 90,
        ];
    }
}

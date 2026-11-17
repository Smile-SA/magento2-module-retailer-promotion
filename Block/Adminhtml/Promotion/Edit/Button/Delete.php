<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Block\Adminhtml\Promotion\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Smile\RetailerPromotion\Block\Adminhtml\Promotion\Edit\AbstractButton;

class Delete extends AbstractButton implements ButtonProviderInterface
{
    /**
     * Get the button data
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getButtonData(): array
    {
        $data = [];
        if ($this->getPromotionId()) {
            $message = htmlentities(__('Are you sure you want to delete this Promotion ?')->render());

            $data = [
                'label'      => __('Delete Promotion'),
                'class'      => 'delete',
                'on_click'   => "deleteConfirm('{$message}', '{$this->getDeleteUrl()}')",
                'sort_order' => 20,
            ];
        }

        return $data;
    }

    /**
     * Get the delete URL.
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getDeleteUrl(): string
    {
        return $this->getUrl('*/*/delete', ['id' => $this->getPromotionId()]);
    }
}

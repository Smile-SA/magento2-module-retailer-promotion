<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Controller\Adminhtml\Promotion;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Smile\RetailerPromotion\Controller\Adminhtml\AbstractPromotion;

/**
 * MassDelete Controller for Promotion
 */
class MassDelete extends AbstractPromotion implements HttpPostActionInterface
{
    /**
     * Execute action
     *
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute(): Redirect
    {
        $promotionIds = $this->getRequest()->getParam('selected');
        foreach ($promotionIds as $id) {
            $model = $this->promotionRepository->get($id);
            $this->promotionRepository->delete($model);
        }

        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) have been deleted.', count($promotionIds))
        );

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}

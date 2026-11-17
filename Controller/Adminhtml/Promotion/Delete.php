<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Controller\Adminhtml\Promotion;

use Exception;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Smile\RetailerPromotion\Controller\Adminhtml\AbstractPromotion;

/**
 * Delete Controller for Promotion
 */
class Delete extends AbstractPromotion implements HttpGetActionInterface
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $identifier = $this->getRequest()->getParam('id', false);
        $model = $this->promotionFactory->create();
        if ($identifier) {
            $model = $this->promotionRepository->get($identifier);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This promotion no longer exists.'));

                return $resultRedirect->setPath('*/*/index');
            }
        }

        try {
            $this->promotionRepository->delete($model);
            $this->messageManager->addSuccessMessage(__('You deleted the promotion %1.', $model->getId()));

            return $resultRedirect->setPath('*/*/index');
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
    }
}

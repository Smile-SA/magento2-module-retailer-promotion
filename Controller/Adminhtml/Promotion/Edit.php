<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Controller\Adminhtml\Promotion;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Smile\RetailerPromotion\Controller\Adminhtml\AbstractPromotion;
use Smile\RetailerPromotion\Model\Status\Source\Status;

/**
 * Retailer Promotion Adminhtml Edit controller.
 */
class Edit extends AbstractPromotion implements HttpGetActionInterface
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $promotionId = (int) $this->getRequest()->getParam('id');
        $isExistingPromotion = (bool) $promotionId;

        if ($isExistingPromotion) {
            try {
                $promotion = $this->promotionRepository->get($promotionId);

                if ($promotion->getStatus() == Status::STATUS_CENTRAL) {
                    $this->messageManager->addErrorMessage(__("You can't edit this central promotion."));
                    $resultRedirect = $this->resultRedirectFactory->create();
                    $resultRedirect->setPath('*/*/index');

                    return $resultRedirect;
                }

                $this->coreRegistry->register('current_promotion', $promotion);

                $resultPage = $this->createPage();
                $resultPage->getConfig()->getTitle()->prepend(
                    __('Edit promotion %1 ', $promotion->getId())
                );
                $resultPage->addBreadcrumb(__('Promotion'), __('Promotion'));

                return $resultPage;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while editing the promotion.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('*/*/index');

                return $resultRedirect;
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);

        return $resultForward->forward('create');
    }
}

<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Controller\Adminhtml\Promotion;

use Exception;
use Magento\Backend\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Smile\RetailerPromotion\Api\Data\PromotionInterface;
use Smile\RetailerPromotion\Controller\Adminhtml\AbstractPromotion;

/**
 * Retailer Promotion Adminhtml Save controller.
 */
class Save extends AbstractPromotion implements HttpPostActionInterface
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /** @var \Magento\Framework\App\Request\Http $request */
        $request      = $this->getRequest();
        $data         = $request->getPostValue();
        $redirectBack = $this->getRequest()->getParam('back', false);

        if ($data) {
            $identifier = $this->getRequest()->getParam('promotion_id');
            $retailerIds = $this->getRequest()->getParam('retailer_id', ['0']);

            $media = false;
            if (
                !empty($data[PromotionInterface::MEDIA_PATH])
                && isset($data[PromotionInterface::MEDIA_PATH][0]['name'])
            ) {
                $media = $data[PromotionInterface::MEDIA_PATH][0]['name'];
            }
            unset($data[PromotionInterface::MEDIA_PATH]);

            /** @var PromotionInterface $model*/
            $model = $this->promotionFactory->create();

            if ($identifier) {
                /** @var \Smile\RetailerPromotion\Model\Promotion $model */
                $model = $this->promotionRepository->get($identifier);
                $retailerIds = [$model->getRetailerId()];
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This promotion no longer exists.'));

                    return $resultRedirect->setPath('*/*/');
                }
            }

            foreach ($retailerIds as $retailerId) {
                $model->setData($data);
                $model->setRetailerId($retailerId);
                if ($media) {
                    $model->setMediaPath($retailerId . '_' . $media);
                }

                try {
                    $this->promotionRepository->save($model);
                    $this->messageManager->addSuccessMessage(__('You saved the promotion %1.', $model->getTitle()));
                    $this->_objectManager->get(Session::class)->setFormData(false);

                    if ($redirectBack) {
                        return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                    }

                    $model = $this->promotionFactory->create();
                } catch (Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                    $this->_objectManager->get(Session::class)->setFormData($data);

                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
            }

            return $resultRedirect->setPath('*/*/');
        }

        return $resultRedirect->setPath('*/*/');
    }
}

<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Controller\Adminhtml\Promotion;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Smile\RetailerPromotion\Controller\Adminhtml\AbstractPromotion;

/**
 * Index Controller for retailer promotion management.
 */
class Index extends AbstractPromotion implements HttpGetActionInterface
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->createPage();
        $resultPage->getConfig()->getTitle()->prepend(__('Retailer Promotions List'));

        return $resultPage;
    }
}

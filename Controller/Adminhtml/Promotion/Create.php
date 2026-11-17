<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Controller\Adminhtml\Promotion;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Smile\RetailerPromotion\Controller\Adminhtml\AbstractPromotion;

/**
 * Retailer Promotion Creation Controller
 */
class Create extends AbstractPromotion implements HttpGetActionInterface
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->coreRegistry->register("current_promotion", $this->promotionFactory->create([]));

        $resultPage = $this->createPage();

        $resultPage->setActiveMenu('Smile_RetailerPromotion::retailer_promotion');
        $resultPage->getConfig()->getTitle()->prepend(__('New Retailer Promotion'));

        return $resultPage;
    }
}

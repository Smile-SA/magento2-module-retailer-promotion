<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Smile\RetailerPromotion\Api\Data\PromotionInterfaceFactory;
use Smile\RetailerPromotion\Api\PromotionRepositoryInterface;

/**
 * Abstract Controller for retailer promotion management.
 *
 * @category Smile
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
abstract class AbstractPromotion extends Action
{
    protected ?PageFactory $resultPageFactory = null;

    /**
     * Core registry
     */
    protected Registry $coreRegistry;

    /**
     * Promotion Repository
     */
    protected PromotionRepositoryInterface $promotionRepository;

    /**
     * Promotion Factory
     */
    protected PromotionInterfaceFactory $promotionFactory;

    /**
     * Abstract constructor.
     *
     * @param Context                      $context             Application context
     * @param PageFactory                  $resultPageFactory   Result Page factory
     * @param Registry                     $coreRegistry        Application registry
     * @param PromotionRepositoryInterface $promotionRepository Promotion Repository
     * @param PromotionInterfaceFactory    $promotionFactory    Promotion Factory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        PromotionRepositoryInterface $promotionRepository,
        PromotionInterfaceFactory $promotionFactory
    ) {
        $this->resultPageFactory   = $resultPageFactory;
        $this->coreRegistry        = $coreRegistry;
        $this->promotionRepository = $promotionRepository;
        $this->promotionFactory    = $promotionFactory;

        parent::__construct($context);
    }

    /**
     * Create result page
     */
    protected function createPage(): Page
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Smile_RetailerPromotion::retailer_promotions')
            ->addBreadcrumb(__('Sellers'), __('Retailers'), __('Promotions'));

        return $resultPage;
    }

    /**
     * Check if allowed to manage promotion
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Smile_RetailerPromotion::retailer_promotions');
    }
}

<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Block\Adminhtml\Promotion\Edit;

use Magento\Backend\Block\Widget\Context;
use Smile\RetailerPromotion\Api\PromotionRepositoryInterface;

/**
 * AbstractButton class
 *
 * @category Smile
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
abstract class AbstractButton
{
    /**
     * AbstractButton constructor.
     *
     * @param Context                      $context          Context.
     * @param PromotionRepositoryInterface $objectRepository Promotion Repository.
     */
    public function __construct(
        protected Context $context,
        protected PromotionRepositoryInterface $objectRepository
    ) {
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route  Route.
     * @param   array  $params Params.
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    /**
     * Return promotion Id.
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getPromotionId(): ?int
    {
        $id = $this->context->getRequest()->getParam('id', false);

        if ($id) {
            return (int) $this->objectRepository->get((int) $id)->getId();
        }

        return null;
    }

    /**
     * Get the button data
     */
    abstract public function getButtonData(): array;
}

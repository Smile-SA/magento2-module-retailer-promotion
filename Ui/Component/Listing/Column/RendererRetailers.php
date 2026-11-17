<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Smile\Retailer\Api\RetailerRepositoryInterface;

class RendererRetailers extends Column
{
    /**
     * @param ContextInterface             $context            Application context
     * @param UiComponentFactory           $uiComponentFactory Ui Component Factory
     * @param RetailerRepositoryInterface  $retailerRepository Retailer repository.
     * @param array                        $components         Components
     * @param array                        $data               Component Data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        protected RetailerRepositoryInterface $retailerRepository,
        array $components = [],
        array $data = []
    ) {

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource The data source
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$this->getData('name')])) {
                    $retailerId = $item[$this->getData('name')];
                    $item[$this->getData('name')] = '';

                    if ($retailerId != '0') {
                        $retailerName = $this->retailerRepository->get((int) $retailerId);
                        $item[$this->getData('name')] = $retailerName->getName();
                    }
                }
            }
        }

        return $dataSource;
    }
}

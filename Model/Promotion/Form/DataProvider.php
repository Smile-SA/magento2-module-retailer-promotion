<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Model\Promotion\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Smile\RetailerPromotion\Api\Data\PromotionInterface;
use Smile\RetailerPromotion\Model\ResourceModel\Promotion\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    // phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
    protected $collection;

    protected array $loadedData;

    /**
     * Constructor
     *
     * @param string                        $name              Name.
     * @param string                        $primaryFieldName  Primary field name.
     * @param string                        $requestFieldName  Request field name.
     * @param CollectionFactory             $collectionFactory Collection.
     * @param DataPersistorInterface        $dataPersistor     Data persistor.
     * @param StoreManagerInterface         $storeManager      Store manager.
     * @param array                         $meta              Meta.
     * @param array                         $data              Data.
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        protected DataPersistorInterface $dataPersistor,
        protected StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection    = $collectionFactory->create();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \Smile\RetailerPromotion\Model\Promotion $promotion */
        foreach ($items as $promotion) {
            $promotion->setData(PromotionInterface::MEDIA_PATH, $this->getMediaUrl($promotion));
            $this->loadedData[$promotion->getId()] = $promotion->getData();
        }

        $data = $this->dataPersistor->get('smile_retailer_promotion');
        if (!empty($data)) {
            $promotion = $this->collection->getNewEmptyItem();
            $promotion->setData($data);

            $this->loadedData[$promotion->getId()] = $promotion->getData();
            $this->dataPersistor->clear('smile_retailer_promotion');
        }

        return $this->loadedData;
    }

    /**
     * Get media Url.
     *
     * @param PromotionInterface $promotion Promotion.
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getMediaUrl(PromotionInterface $promotion): array
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();
        $mediaUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        return [
            0 => [
                'name' => $promotion->getMediaPath(),
                'url' => $mediaUrl . 'retailerpromotion/' . $promotion->getMediaPath(),
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Model\ResourceModel;

use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime;
use Smile\RetailerPromotion\Api\Data\PromotionInterface;
use Smile\RetailerPromotion\Model\PromotionMediaUpload;

/**
 * Promotion Resource Model
 */
class Promotion extends AbstractDb
{
    /**
     * Promotion constructor.
     *
     * @param Context              $context        Application Context
     * @param EntityManager        $entityManager  Entity Manager
     * @param MetadataPool         $metadataPool   Metadata Pool
     * @param DateTime             $dateTime       Datetime
     * @param PromotionMediaUpload $mediaUpload    Media upload
     * @param string               $connectionName Connection name
     */
    public function __construct(
        Context $context,
        protected EntityManager $entityManager,
        protected MetadataPool $metadataPool,
        protected DateTime $dateTime,
        protected PromotionMediaUpload $mediaUpload,
        ?string $connectionName = null
    ) {

        parent::__construct($context, $connectionName);
    }

    /**
     * @inheritdoc
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(PromotionInterface::class)->getEntityConnection();
    }

    /**
     * Load a promotion by a given field's value.
     *
     * @param \Magento\Framework\Model\AbstractModel $object The promotion
     * @param mixed                                  $value  The value
     * @param mixed                                  $field  The field, if any
     * @return $this
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $promotionId = $this->getPromotionId($object, $value, $field);
        if ($promotionId) {
            $this->entityManager->load($object, (string) $promotionId);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function save(AbstractModel $object)
    {
        foreach ([PromotionInterface::CREATED_AT, PromotionInterface::END_AT] as $field) {
            $value = !$object->getData($field) ? null : $this->dateTime->formatDate($object->getData($field));
            $object->setData($field, $value);
        }

        $this->entityManager->save($object);
        /** @var \Smile\RetailerPromotion\Model\Promotion $object */
        $this->mediaUpload->removeMediaFromTmp($object);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        /** @var \Smile\RetailerPromotion\Model\Promotion $object */
        $this->mediaUpload->removeMedia($object);

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(
            PromotionInterface::TABLE_NAME,
            PromotionInterface::PROMOTION_ID
        );
    }

    /**
     * Retrieve promotion Id by field value
     *
     * @param \Magento\Framework\Model\AbstractModel $object The promotion
     * @param mixed                                  $value  The value
     * @param string|null                            $field  The field
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getPromotionId(AbstractModel $object, $value, ?string $field = null)
    {
        $entityMetadata = $this->metadataPool->getMetadata(PromotionInterface::class);
        if ($field === null) {
            $field = PromotionInterface::PROMOTION_ID;
        }

        $entityId = $value;

        if ($field != $entityMetadata->getIdentifierField()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                ->limit(1);

            $result = $this->getConnection()->fetchCol($select);
            $entityId = count($result) ? $result[0] : false;
        }

        return $entityId === false ? false : (int) $entityId;
    }
}

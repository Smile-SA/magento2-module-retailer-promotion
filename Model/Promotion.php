<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Smile\RetailerPromotion\Api\Data\PromotionInterface;

/**
 * Promotion Model
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName) properties are inherited.
 */
class Promotion extends AbstractModel implements PromotionInterface, IdentityInterface
{
    public const CACHE_TAG = self::TABLE_NAME;

    // phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Construct.
     */
    protected function _construct(): void
    {
        $this->_init(\Smile\RetailerPromotion\Model\ResourceModel\Promotion::class);
    }

    /**
     * Get identities
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getData(self::PROMOTION_ID);
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritdoc
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritdoc
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritdoc
     */
    public function getMediaPath()
    {
        return $this->getData(self::MEDIA_PATH);
    }

    /**
     * @inheritdoc
     */
    public function getPdf()
    {
        return $this->getData(self::PDF);
    }

    /**
     * @inheritdoc
     */
    public function getLink()
    {
        return $this->getData(self::LINK);
    }

    /**
     * @inheritdoc
     */
    public function getRetailerId()
    {
        return $this->getData(self::RETAILER_ID);
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function getEndAt()
    {
        return $this->getData(self::END_AT);
    }

    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        return $this->setData(self::PROMOTION_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritdoc
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritdoc
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritdoc
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @inheritdoc
     */
    public function setMediaPath($path)
    {
        return $this->setData(self::MEDIA_PATH, $path);
    }

    /**
     * @inheritdoc
     */
    public function setPdf($path)
    {
        return $this->setData(self::PDF, $path);
    }

    /**
     * @inheritdoc
     */
    public function setLink($path)
    {
        return $this->setData(self::LINK, $path);
    }

    /**
     * @inheritdoc
     */
    public function setRetailerId($retailerId)
    {
        return $this->setData(self::RETAILER_ID, $retailerId);
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritdoc
     */
    public function setEndAt($endAt)
    {
        return $this->setData(self::END_AT, $endAt);
    }
}

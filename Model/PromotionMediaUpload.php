<?php

declare(strict_types=1);

namespace Smile\RetailerPromotion\Model;

use Exception;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Smile\RetailerPromotion\Api\Data\PromotionInterface;

/**
 * PromotionMediaUpload Model
 */
class PromotionMediaUpload
{
    /**
     * Image Uploader.
     */
    protected ImageUploader $imageUploader;

    /**
     * Directory List.
     */
    protected DirectoryList $directoryList;

    /**
     * Core file storage database
     */
    protected Database $coreFileStorageDatabase;

    /**
     * Media directory object (writable).
     */
    protected WriteInterface $mediaDirectory;

    /**
     * PromotionMediaUpload constructor.
     *
     * @param ImageUploader $imageUploader           Image uploader
     * @param DirectoryList $directoryList           Directory List.
     * @param Database      $coreFileStorageDatabase File Storage Database.
     * @param Filesystem    $filesystem              File System.
     * @throws FileSystemException
     */
    public function __construct(
        ImageUploader $imageUploader,
        DirectoryList $directoryList,
        Database $coreFileStorageDatabase,
        Filesystem $filesystem
    ) {
        $this->imageUploader           = $imageUploader;
        $this->directoryList           = $directoryList;
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory          = $filesystem->getDirectoryWrite(
            DirectoryList::MEDIA
        );
    }

    /**
     * Remove media From Tmp Directory.
     *
     * @param PromotionInterface $promotion Promotion.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeMediaFromTmp(PromotionInterface $promotion): void
    {
        $media = str_replace($promotion->getRetailerId() . '_', '', (string) $promotion->getMediaPath());
        $baseTmpPath = $this->imageUploader->getBaseTmpPath();
        if (!empty($media) && $this->pathExist($baseTmpPath, $media)) {
            $this->moveFileFromTmp($media, $promotion);
        }
    }

    /**
     * Move media From Tmp Directory.
     *
     * @param string             $imageName Image name.
     * @param PromotionInterface $promotion Promotion.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function moveFileFromTmp(string $imageName, PromotionInterface $promotion): void
    {
        $baseTmpPath = $this->imageUploader->getBaseTmpPath();
        $basePath = $this->imageUploader->getBasePath();

        $baseImagePath = $this->imageUploader->getFilePath(
            $basePath,
            $promotion->getRetailerId() . '_' . $imageName
        );
        $baseTmpImagePath = $this->imageUploader->getFilePath($baseTmpPath, $imageName);

        try {
            $this->coreFileStorageDatabase->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }
    }

    /**
     * Remove File.
     *
     * @param PromotionInterface $promotion Promotion.
     * @throws FileSystemException
     */
    public function removeMedia(PromotionInterface $promotion): void
    {
        $media = $promotion->getRetailerId() . '_' . $promotion->getMediaPath();
        $basePath = $this->imageUploader->getBasePath();
        if ($promotion->getMediaPath() && $this->pathExist($basePath, $media)) {
            $this->removeFile($media);
        }
    }

    /**
     * Check if media exist.
     *
     * @param string $basePath Base Path.
     * @param string $fileName File Name.
     * @throws FileSystemException
     */
    public function pathExist(string $basePath, string $fileName): bool
    {
        return file_exists($this->getPath($basePath, $fileName));
    }

    /**
     * Get media Path.
     *
     * @param string $basePath Base Path.
     * @param string $fileName File Name.
     * @throws FileSystemException
     */
    public function getPath(string $basePath, string $fileName): string
    {
        return $this->directoryList->getPath(DirectoryList::MEDIA) .
            '/'
            . $this->imageUploader->getFilePath($basePath, $fileName);
    }

    /**
     * Remove File.
     *
     * @param string $fileName File Name.
     * @throws FileSystemException
     */
    public function removeFile(string $fileName): void
    {
        unlink($this->getPath($this->imageUploader->getBasePath(), $fileName));
    }
}

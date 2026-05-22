<?php

namespace Rptech\Leadership\Plugin;

use Exception;
use Rptech\Leadership\Model\Leadership\File;
use Rptech\Leadership\Model\ImageUploader;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Model\AbstractModel;
use Rptech\Leadership\Model\ResourceModel\Leadership as LeadershipResource;
use Psr\Log\LoggerInterface;

class ImageProcessingPlugin
{
    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var File
     */
    private $file;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var RedundantLeadershipImageChecker
     */
    private $redundantLeadershipImageChecker;

    /**
     * RemoveImagePlugin constructor.
     * @param File $file
     * @param ImageUploader $imageUploader
     * @param RedundantLeadershipImageChecker $redundantLeadershipImageChecker
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Rptech\Leadership\Model\Leadership\File $file,
        \Rptech\Leadership\Model\ImageUploader $imageUploader,
        \Rptech\Leadership\Model\ResourceModel\RedundantLeadershipImageChecker $redundantLeadershipImageChecker,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->file = $file;
        $this->imageUploader = $imageUploader;
        $this->redundantLeadershipImageChecker = $redundantLeadershipImageChecker;
        $this->logger = $logger;
    }

    /**
     * @param LeadershipResource $subject
     * @param AbstractModel $brand
     */
    public function beforeSave(LeadershipResource $subject, AbstractModel $brand)
    {
        $value = $brand->getData('image');
        if ($imageName = $this->getUploadedImageName($value)) {
            $brand->setData('image', $value[0]['name']);
            $brand->setData('image_obj', $value);
        } elseif (!is_string($value)) {
            $brand->setData('image', null);
        }
    }

    /**
     * @param LeadershipResource $subject
     * @param $result
     * @param AbstractModel $brand
     * @return LeadershipResource
     * @throws FileSystemException
     */
    public function afterSave(LeadershipResource $subject, $result, AbstractModel $brand): LeadershipResource
    {

        $value = $brand->getData('image_obj');
        if ($this->isTmpFileAvailable($value) && $imageName = $this->getUploadedImageName($value)) {
            try {
                $this->imageUploader->moveFileFromTmp($imageName);
            } catch (Exception $e) {
                $this->logger->critical($e);
            }
        }

        //Remove Image
        $originalImage = $brand->getOrigData('image');
        if (null !== $originalImage
            && $originalImage !== $brand->getData('image')
            && $this->redundantLeadershipImageChecker->execute($originalImage)
        ) {
            $this->file->delete($originalImage);
            $this->file->cleanupCacheImages($originalImage);
        }
        return  $result;
    }

    /**
     * @param LeadershipResource $subject
     * @param $result
     * @param AbstractModel $brand
     * @return LeadershipResource
     * @throws FileSystemException
     */
    public function afterDelete(LeadershipResource $subject, $result, AbstractModel $brand): LeadershipResource
    {
        $image = $brand->getData('image');
        if ($image && $this->redundantLeadershipImageChecker->execute($image)) {
            $this->file->delete($image);
            $this->file->cleanupCacheImages($image);
        }
        return $result;
    }

    /**
     * Check if temporary file is available for new image upload.
     *
     * @param array $value
     * @return bool
     */
    private function isTmpFileAvailable($value)
    {
        return is_array($value) && isset($value[0]['tmp_name']);
    }

    /**
     * Gets image name from $value array.
     * Will return empty string in a case when $value is not an array
     *
     * @param array $value Attribute value
     * @return string
     */
    private function getUploadedImageName($value)
    {
        if (is_array($value) && isset($value[0]['name'])) {
            return $value[0]['name'];
        }

        return '';
    }
}

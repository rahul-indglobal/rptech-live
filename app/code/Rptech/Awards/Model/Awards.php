<?php
/**
 * @author Rptech
 * @package Rptech_Awards
 */
namespace Rptech\Awards\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Rptech\Awards\Model\Awards\File;

/**
 * Class Awards
 * @package Rptech\Awards\Model
 */
class Awards extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'awards';

    protected $_cacheTag = 'awards';

    protected $_eventPrefix = 'awards';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var File
     */
    protected $file;

    protected function _construct()
    {
        $this->_init('\Rptech\Awards\Model\ResourceModel\Awards');
    }

    /**
     * Awards constructor.
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param File $file
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Rptech\Awards\Model\Awards\File $file,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->file = $file;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

    /**
     * Returns image url
     *
     * @param string $attributeCode
     * @return bool|string
     * @throws LocalizedException
     */
    public function getImageUrl($attributeCode = 'image')
    {
        $url = false;
        $image = $this->getData($attributeCode);
        if ($image) {
            if (is_string($image)) {
                $store = $this->storeManager->getStore();

                $isRelativeUrl = substr($image, 0, 1) === '/';

                $mediaBaseUrl = $store->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                );

                if ($isRelativeUrl) {
                    $url = $image;
                } else {
                    $url = $mediaBaseUrl
                        . ltrim(File::ENTITY_MEDIA_PATH, '/')
                        . '/'
                        . $image;
                }
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }
}
<?php
namespace Rptech\FinancialReport\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Rptech\FinancialReport\Model\FinancialReport\File;

class FinancialReport extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'financial_report';

    protected $_cacheTag = 'financial_report';

    protected $_eventPrefix = 'financial_report';
    /**
     * @var File
     */
    protected $file;

    protected function _construct()
    {
        $this->_init('Rptech\FinancialReport\Model\ResourceModel\FinancialReport');
    }

    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Rptech\FinancialReport\Model\FinancialReport\File $file,
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
    public function getDocumentUrl($attributeCode = 'document')
    {
        $url = false;
        $doucment = $this->getData($attributeCode);
        if ($doucment) {
            if (is_string($doucment)) {
                $store = $this->storeManager->getStore();

                $isRelativeUrl = substr($doucment, 0, 1) === '/';

                $mediaBaseUrl = $store->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                );

                if ($isRelativeUrl) {
                    $url = $doucment;
                } else {
                    $url = $mediaBaseUrl
                        . ltrim(File::ENTITY_MEDIA_PATH, '/')
                        . '/'
                        . $doucment;
                }
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

    /**
     * Define the relationship with the child table
     *
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getDocItems()
    {
        return $this->getCollection()->getChildItems($this);
    }
}
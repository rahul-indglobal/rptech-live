<?php

namespace Rptech\Brand\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Rptech\Brand\Model\ImageUploader;

/**
 * Class Upload
 * @package Rptech\Brand\Controller\Adminhtml\Admin
 */
class Upload extends \Magento\Backend\App\Action
{
    /**
     * @var ImageUploader
     */
    public $imageUploader;

    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Rptech_Brand::save');
    }

    public function execute()
    {
        try {
            $image = $this->_objectManager->get(\Rptech\Brand\Model\ImageUploader::class);
            $result = $image->saveFileToTmpDir('image');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
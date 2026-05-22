<?php

namespace Rptech\Awards\Controller\Adminhtml\Admin;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Rptech\Awards\Model\ImageUploader;

/**
 * Class Upload
 * @package Rptech\Awards\Controller\Adminhtml\Admin
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
        return $this->_authorization->isAllowed('Rptech_Awards::save');
    }

    public function execute()
    {
        try {
            /**
             * @var \Rptech\Awards\Model\ImageUploader $image
             */
            $image = $this->_objectManager->get(\Rptech\Awards\Model\ImageUploader::class);
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
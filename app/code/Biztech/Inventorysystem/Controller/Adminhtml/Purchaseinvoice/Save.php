<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Purchaseinvoice;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{

    protected $_purchaseinvoiceModel;
    protected $_backendSession;

    /**
     * @param \Biztech\Inventorysystem\Model\Purchaseinvoice $purchaseinvoiceModel
     * @param \Magento\Framework\Registry                    $registry
     * @param \Magento\Backend\Model\Session                 $backendSession
     */
    public function __construct(
        \Biztech\Inventorysystem\Model\Purchaseinvoice $purchaseinvoiceModel,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\Session $backendSession
    ) {
        $this->_purchaseinvoiceModel = $purchaseinvoiceModel;
        $this->_backendSession = $backendSession;
    }

    /**
     * This function is used for save purchase invoice
     * @return Void
     */
    public function execute()
    {
        
        $data = $this->getRequest()->getParams();
        if ($data) {
            $model = $this->_purchaseinvoiceModel;
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }
            
            $model->setData($data);
            
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Frist Grid Has been Saved.'));
                $this->_backendSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
}

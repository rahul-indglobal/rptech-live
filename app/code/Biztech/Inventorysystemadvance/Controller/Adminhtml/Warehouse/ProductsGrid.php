<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Warehouse;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class ProductsGrid extends \Magento\Backend\App\Action
{
    protected $_resultLayoutFactory;

    /**
     * @param Action\Context                               $context
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->_resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('wsproductsgrid.edit.tab.products')
                ->setInBanner($this->getRequest()->getPost('warehouse_products', null));

        return $resultLayout;
    }
}

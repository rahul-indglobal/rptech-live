<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Pendingitems;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\View\Result\PageFactory;

class ExportCsv extends Action
{
    protected $resultPageFactory;
    protected $resultPage;
    protected $_fileFactory;
    protected $_request;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        FileFactory $fileFactory,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_fileFactory = $fileFactory;
        $this->_request = $request;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Exception
     */
    public function execute()
    {
        
        $selectedProducts = $this->_request->getParam('pendingitems');
        
        $this->resultPage = $this->resultPageFactory->create();

        $fileName = 'pending_products.csv';
        $grid = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystem\Block\Adminhtml\Pendingitems\Grid');
        $content = $grid->getCSVExtended($selectedProducts);

        return $this->_fileFactory->create(
            $fileName,
            $content,
            DirectoryList::VAR_DIR
        );
    }
}

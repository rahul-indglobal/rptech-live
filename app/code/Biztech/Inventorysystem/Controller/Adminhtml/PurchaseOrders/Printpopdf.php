<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\PurchaseOrders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Dompdf\Dompdf;
use Dompdf\Options;
use Magento\Framework\Controller\ResultFactory;

class Printpopdf extends Action
{
    protected $resultPageFactory;
    protected $resultPage;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * This function is used for print PO
     * @return Object
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $this->resultPage = $this->resultPageFactory->create();
        if (class_exists('Dompdf\Options')) {
            $content = $this->resultPage->getLayout()->createBlock('Biztech\Inventorysystem\Block\Adminhtml\Purchaseorders\PrintPO')->setTemplate('Biztech_Inventorysystem::purchaseorders/printpo.phtml')->toHtml();

            $this->createPDF($content, 'purchaseorder.pdf');

            return $this->resultPage;
        } else {
            $this->messageManager->addError(__('Please run following command on root without inverted commas "composer require dompdf/dompdf" '));
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
    }

    /**
     * This function is used for the create the PDF
     * @param  Object $content
     * @param  Strinf $name
     * @return Void
     */
    private function createPDF($content, $name)
    {
            $domOptions = new Options();
            $domOptions->setIsPhpEnabled(true);
            $domOptions->setIsHtml5ParserEnabled(true);
            $dompdf = new Dompdf($domOptions);
            $dompdf->loadHtml($content);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream($name);
    }
}

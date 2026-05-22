<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Inventorysystem\Controller\Adminhtml\Managesupplier;

use Magento\Backend\App\Action;
use Magento\Directory\Model\CountryFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class State extends Action
{
    protected $resultPageFactory;
    protected $_countryFactory;
    protected $_jsHelperData;

    /**
     * @param Context                             $context
     * @param CountryFactory                      $countryFactory
     * @param PageFactory                         $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data $jsHelperData
     */
    public function __construct(
        Context $context,
        CountryFactory $countryFactory,
        PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsHelperData
    ) {
        $this->_countryFactory = $countryFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_jsHelperData = $jsHelperData;
        parent::__construct($context);
    }

    /**
     * Default customer account page
     *
     * @return void
     */
    public function execute()
    {
        $countrycode = $this->getRequest()->getParam('country');
        $state = "";
        if ($countrycode != '') {
            $statearray = $this->_countryFactory->create()->setId(
                $countrycode
            )->getLoadedRegionCollection()->toOptionArray();
            if (count($statearray) > 0) {
                foreach ($statearray as $_state) {
                    if ($_state['value']) {
                        $state .= "<option value=" . $_state["value"] . ">" . $_state['label'] . "</option>";
                    }
                }
            }
        }
        $result['htmlconent'] = $state;
        $this->getResponse()->representJson(
            $this->_jsHelperData->jsonEncode($result)
        );
    }
}

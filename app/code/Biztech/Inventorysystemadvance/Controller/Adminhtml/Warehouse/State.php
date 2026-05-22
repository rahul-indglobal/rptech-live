<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Controller\Adminhtml\Warehouse;

class State extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    protected $_countryFactory;
    protected $_jsonHelper;

    /**
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Directory\Model\CountryFactory    $countryFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data        $jsonHelper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        $this->_countryFactory = $countryFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * This function is used for get the states and country
     * @return object
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
            $this->_jsonHelper->jsonEncode($result)
        );
    }
}

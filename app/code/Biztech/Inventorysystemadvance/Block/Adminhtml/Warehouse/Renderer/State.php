<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Block\Adminhtml\Warehouse\Renderer;

class State extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    protected $_regionModel;

    /**
     * @param \Magento\Directory\Model\Region $regionModel
     */
    public function __construct(\Magento\Directory\Model\Region $regionModel)
    {
        $this->_regionModel = $regionModel;
    }
    /**
     * This function is used for get state
     * @param  \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $txtbox = '';
        if ($this->getColumn()->getIndex() == 'state') {
            if (is_numeric($row->getState())) {
                 $Region = $this->_regionModel;
                $Region = $Region->load($row->getState());
                $txtbox .= "<span>" . $Region->getName() . "</span>";
            } else {
                $txtbox .= "<span>" . $row->getState() . "</span>";
            }
        }
        return $txtbox;
    }
}

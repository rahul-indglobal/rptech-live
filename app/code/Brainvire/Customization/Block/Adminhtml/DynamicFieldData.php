<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 **/
namespace Brainvire\Customization\Block\Adminhtml;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use RH\Helloworld\Block\Adminhtml\Form\Field\CustomColumn;

class DynamicFieldData extends AbstractFieldArray
{
    /**
     * @var CustomColumn
     */
    private $dropdownRenderer;

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareToRender()
    {
        $this->addColumn('plant', ['label' => __('Plant'), 'class' => 'required-entry']);
        $this->addColumn('storageloc', ['label' => __('Storage Location'), 'class' => 'required-entry']);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}

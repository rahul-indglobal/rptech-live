<?php

namespace Rptech\Media\Block\Adminhtml\Data\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveAndContinue
 * @package Rptech\Media\Block\Adminhtml\Data\Edit\Buttons
 */
class SaveAndContinue extends Generic implements ButtonProviderInterface
{

    /**
     * Get buttong attributes
     *
     * @return array
     */
    public function getButtonData()
    {
        [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['media' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order' => 80,
        ];
        return [];
    }
}

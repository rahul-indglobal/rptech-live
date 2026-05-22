<?php

namespace Rptech\QuantumRma\Block\Adminhtml\QuantumRma;

class Form extends \Magento\Backend\Block\Widget\Form\Container {

    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry           $registry
     * @param array                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        $this->_request = $request;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize Imagegallery Images Edit Block.
     */
    protected function _construct() {
        $this->_objectId = 'row_id';
        $this->_blockGroup = 'Rptech_QuantumRma';
        $this->_controller = 'adminhtml_quantumRma';
        parent::_construct();
        if ($this->_isAllowedAction('Rptech_QuantumRma::quantumrma')) {
            $this->buttonList->update('save', 'label', __('Save'));
        } else {
            $this->buttonList->remove('save');
        }
        $this->buttonList->remove('reset');
        $id = $this->_request->getParam('id');
        if($id!=''){
            $this->addButton(
                'delete',
                [
                    'label' => __('Delete'),
                    'on_click' => sprintf("location.href = '%s';", $this->getUrl('quantum_support/quantumrma/delete', array('id' => $id))),
                    'class' => 'primary',
                    'level' => 10
                ]
            );
        }
    }

    /**
     * Retrieve text for header element depending on loaded image.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText() {
        return __('Add Form');
    }

    /**
     * Check permission for passed action.
     *
     * @param string $resourceId
     *
     * @return bool
     */
    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Get form action URL.
     *
     * @return string
     */
    public function getFormActionUrl() {
        if ($this->hasFormActionUrl()) {
            return $this->getData('form_action_url');
        }

        return $this->getUrl('*/*/save');
    }

}

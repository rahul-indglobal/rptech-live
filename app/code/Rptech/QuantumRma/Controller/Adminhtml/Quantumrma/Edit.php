<?php
namespace Rptech\QuantumRma\Controller\Adminhtml\Quantumrma;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'Rptech_QuantumRma::quantumrma';

    protected $resultPageFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Edit construct
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $coreRegistry\
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
    }

	public function execute()
	{
        $rowId = $this->getRequest()->getParam('id');
        $rowData = $this->_objectManager->create('Rptech\QuantumRma\Model\QuantumRma');
        if ($rowId) {
            $rowData = $rowData->load($rowId);
            if (!$rowData->getId()) {
                $this->messageManager->addErrorMessage(__('Form is no longer exist.'));
                $this->_redirect('quantum_support/quantumrma/index');
                return;
            }
        }
        $this->_coreRegistry->register('row_data', $rowData);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend($rowId ? __('Edit Record') : __('New Record'));
        return $resultPage;
	}


    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Rptech_QuantumRma::quantumrma');
    }
}

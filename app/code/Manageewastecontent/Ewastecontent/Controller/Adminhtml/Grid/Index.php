<?php
/**
 * Grid Record Index Controller.
 * @category  Manageewastecontent\Ewastecontent 
 * @package   Manageewastecontent\Ewastecontent
 * @author    Lalita Rajput
 */
namespace Manageewastecontent\Ewastecontent\Controller\Adminhtml\Grid;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Mapped eBay Order List page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Manageewastecontent_Ewastecontent::grid_list');
        $resultPage->getConfig()->getTitle()->prepend(__('E-Waste Content Management'));
        return $resultPage;
    }

    /**
     * Check Order Import Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Manageewastecontent_Ewastecontent::grid_list');
    }
}

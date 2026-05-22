<?php
/**
 * @author Rptech
 * @package Rptech_HomepageSlider
 */
namespace Rptech\HomepageSlider\Controller\Adminhtml\Slider;

use Rptech\HomepageSlider\Model\HomepageSliderFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Class Delete
 * @package Rptech\HomepageSlider\Controller\Adminhtml\Slider
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Rptech_HomepageSlider::delete';

    /**
     * @var HomepageSliderFactory
     */
    private $homepageSliderFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param HomepageSliderFactory $homepageSliderFactory
     */
    public function __construct(
        Action\Context $context,
        HomepageSliderFactory $homepageSliderFactory
    ) {
        $this->homepageSliderFactory = $homepageSliderFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($entityId) {
            try {
                $model = $this->homepageSliderFactory->create();
                $model->load($entityId);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The Slider record has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $entityId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a Slider to delete.'));
        return $resultRedirect->setPath('*/*');
    }
}

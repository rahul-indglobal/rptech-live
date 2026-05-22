<?php
namespace Rptech\Webinar\Controller\Adminhtml\Webinar;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Rptech\Webinar\Model\ResourceModel\Webinar\CollectionFactory;
use Magento\Framework\Controller\Result\RedirectFactory;

class Massdelete extends Action
{
	protected $filter;
	protected $collectionFactory;
	protected $redirectFactory;

	public function __construct(
		Action\Context $context,
		Filter $filter,
		CollectionFactory $collectionFactory,
		RedirectFactory $redirectFactory
	) {
		parent::__construct($context);
		$this->filter = $filter;
		$this->collectionFactory = $collectionFactory;
		$this->redirectFactory = $redirectFactory;
	}

	public function execute()
	{
		try {
			$collection = $this->filter->getCollection($this->collectionFactory->create());
			$deleted = 0;

			foreach ($collection as $item) {
				$item->delete();
				$deleted++;
			}

			$this->messageManager->addSuccessMessage(__('%1 record(s) deleted.', $deleted));
		} catch (\Exception $e) {
			$this->messageManager->addErrorMessage(__('Something went wrong while deleting records.'));
		}

		return $this->resultRedirectFactory->create()->setPath('*/*/');
	}

}

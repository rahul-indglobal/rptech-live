<?php
namespace Rptech\Lead\Controller\Adminhtml\Index;

use Rptech\Lead\Model\ResourceModel\DellLead\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
	/**
	 * @var CollectionFactory
	 */
	protected $collectionFactory;
	/**
	 * @var Filter
	 */
	protected Filter $filter;

	/**
	 * @param Context $context
	 * @param Filter $filter
	 * @param CollectionFactory $collectionFactory
	 */
	public function __construct(
		Action\Context $context,
		Filter $filter,
		CollectionFactory $collectionFactory
	) {
		parent::__construct($context);
		$this->collectionFactory = $collectionFactory;
		$this->filter = $filter;
	}

	/**
	 * Execute mass delete
	 */
	public function execute()
	{
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		try {
			$collection   = $this->filter->getCollection($this->collectionFactory->create());
			$deletedCount = 0;

			foreach ($collection as $employee) {
				$employee->delete();
				$deletedCount++;
			}

			if ($deletedCount) {
				$this->messageManager->addSuccessMessage(
					__('A total of %1 record(s) have been deleted.', $deletedCount)
				);
			} else {
				$this->messageManager->addNoticeMessage(__('No records were deleted.'));
			}
		} catch (\Exception $e) {
			$this->messageManager->addErrorMessage(
				__('Error occurred while deleting records: %1', $e->getMessage())
			);
		}

		return $resultRedirect->setPath('*/*/index');
	}
}

<?php
/**
 * @author Rptech
 * @package Rptech_FileUpload
 */
namespace Rptech\FileUpload\Controller\Adminhtml\Admin;

use Rptech\FileUpload\Model\ResourceModel\FileUpload\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete
 * @package Rptech\FileUpload\Controller\Adminhtml\Admin
 */
class MassDelete extends Action
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;
    
    /**
     * MassDelete constructor.
     * @param Action\Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $model) {
            $filename = $model->getFileName();
            $result = $model->delete();
            if ($result) {
                $filePath = $this->mediaDirectory->getAbsolutePath('fileupload/') . $filename;
                if ($this->mediaDirectory->isFile($filePath)) {
                    $this->mediaDirectory->delete($filePath);
                }
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}

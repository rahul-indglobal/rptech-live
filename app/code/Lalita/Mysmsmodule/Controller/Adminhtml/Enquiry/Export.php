<?php

namespace Lalita\Mysmsmodule\Controller\Adminhtml\Enquiry;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Lalita\Mysmsmodule\Model\ResourceModel\Enquiry\CollectionFactory;

/**
 * Class Export
 * @package Rptech\Feedback\Controller\Adminhtml\Index.
 */
class Export extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;


    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;


    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * Export constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param DirectoryList $directoryList
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ForwardFactory $resultForwardFactory,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->fileFactory = $fileFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            if ($collection->getSize()==0) {
                $collection = $this->collectionFactory->create();
            }
            $content = [];
            $content[] = $this->getCsvHeader();
            $fileName = 'enquiry_listing_' . time() . '.csv';
            $filePath = $this->directoryList->getPath(DirectoryList::MEDIA) . "/" . $fileName;
            /**
             * @var \Lalita\Mysmsmodule\Model\Enquiry $eachCollection
             */
            foreach ($collection as $eachCollection) {
                $content[] = [
                    $eachCollection->getId(),
                    $eachCollection->getUsername(),
                    $eachCollection->getUseremail(),
                    $eachCollection->getSku(),
                    $eachCollection->getProductname(),
                    $eachCollection->getMobile(),
                    $eachCollection->getComments(),
                    $eachCollection->getDate(),
                ];
            }
            $this->csvProcessor->setEnclosure('"')->setDelimiter(',')->saveData($filePath, $content);
            return $this->fileFactory->create(
                $fileName,
                [
                    'type' => "filename",
                    'value' => $fileName,
                    'rm' => true,
                ],
                DirectoryList::MEDIA,
                'text/csv',
                null
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __($e->getMessage()));
        }
        $redirectResult = $this->resultRedirectFactory->create();
        $redirectResult->setPath('mysmsmodul/enquiry/index');
        return $redirectResult;
    }

    /**
     * @return array
     */
    protected function getCsvHeader()
    {
        return [
            'entity_id' => __('Entity ID'),
            'username' => __('Name'),
            'useremail' => __('Email'),
            'sku' => __('Sku'),
            'productname' => __('Product'),
            'mobile' => __('Mobile'),
            'comments' => __('Comment'),
            'date' => __('Date'),
        ];
    }
}

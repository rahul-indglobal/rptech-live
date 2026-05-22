<?php

namespace Rptech\Feedback\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Rptech\Feedback\Api\FeedbackRepositoryInterface;
use Rptech\Feedback\Controller\Adminhtml\AbstractFeedback;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Rptech\Feedback\Model\ResourceModel\Feedback\CollectionFactory;

/**
 * Class Export
 * @package Rptech\Feedback\Controller\Adminhtml\Index.
 */
class Export extends AbstractFeedback
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
     * @var FeedbackRepositoryInterface
     */
    protected $dataRepository;

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
     * MassAction constructor.
     *
     * @param Filter $filter
     * @param Registry $registry
     * @param FeedbackRepositoryInterface $dataRepository
     * @param PageFactory $resultPageFactory
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     */
    public function __construct(
        Filter $filter,
        Registry $registry,
        FeedbackRepositoryInterface $dataRepository,
        PageFactory $resultPageFactory,
        Context $context,
        CollectionFactory $collectionFactory,
        ForwardFactory $resultForwardFactory,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    )
    {
        $this->filter = $filter;
        $this->dataRepository = $dataRepository;
        $this->collectionFactory = $collectionFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->fileFactory = $fileFactory;
        parent::__construct(
            $registry,
            $dataRepository,
            $resultPageFactory,
            $resultForwardFactory,
            $context
        );
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $content = [];
            $content[] = $this->getCsvHeader();
            $fileName = 'customer_feedback_' . time() . '.csv';
            $filePath = $this->directoryList->getPath(DirectoryList::MEDIA) . "/" . $fileName;
            /**
             * @var \Rptech\Feedback\Model\Feedback $eachCollection
             */
            foreach ($collection as $eachCollection) {
                $content[] = [
                    $eachCollection->getEntityId(),
                    $eachCollection->getType(),
                    $eachCollection->getName(),
                    $eachCollection->getDescription(),
                    $eachCollection->getEmail(),
                    $eachCollection->getPhone(),
                    $eachCollection->getAddress(),
                    $eachCollection->getState(),
                    $eachCollection->getMobile(),
                    $eachCollection->getPincode(),
                    $eachCollection->getCity(),
                    $eachCollection->getCreatedAt(),
                    $eachCollection->getUpdatedAt(),
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
        $redirectResult->setPath('feedback/index/index');
        return $redirectResult;
    }

    /**
     * @return array
     */
    protected function getCsvHeader()
    {
        return [
            'entity_id' => __('Entity ID'),
            'type' => __('Type'),
            'name' => __('Name'),
            'description' => __('Description'),
            'email' => __('Email'),
            'phone' => __('Phone'),
            'address' => __('Address'),
            'state' => __('State'),
            'mobile' => __('Mobile'),
            'pincode' => __('Pincode'),
            'city' => __('City'),
            'created_at' => __('Created At'),
            'updated_at' => __('Updated At'),
        ];
    }
}

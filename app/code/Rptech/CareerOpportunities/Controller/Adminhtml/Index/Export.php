<?php

namespace Rptech\CareerOpportunities\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Rptech\CareerOpportunities\Api\CareerOpportunitiesRepositoryInterface;
use Rptech\CareerOpportunities\Controller\Adminhtml\AbstractCareerOpportunities;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Rptech\CareerOpportunities\Model\ResourceModel\CareerOpportunities\CollectionFactory;

/**
 * Class Export
 * @package Rptech\CareerOpportunities\Controller\Adminhtml\Index
 */
class Export extends AbstractCareerOpportunities
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
     * @var CareerOpportunitiesRepositoryInterface
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
     * @param CareerOpportunitiesRepositoryInterface $dataRepository
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
        CareerOpportunitiesRepositoryInterface $dataRepository,
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
            $fileName = 'career_application_' . time() . '.csv';
            $filePath = $this->directoryList->getPath(DirectoryList::MEDIA) . "/" . $fileName;
            /**
             * @var \Rptech\CareerOpportunities\Model\CareerOpportunities $eachCollection
             */
            foreach ($collection as $eachCollection) {
                $content[] = [
                    $eachCollection->getEntityId(),
                    $eachCollection->getName(),
                    $eachCollection->getPost(),
                    $eachCollection->getCity(),
                    $eachCollection->getAge(),
                    $eachCollection->getQualification(),
                    $eachCollection->getExperience(),
                    $eachCollection->getEmail(),
                    $eachCollection->getMobile(),
                    $eachCollection->getRemark(),
                    $eachCollection->getCvFile(),
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
        $redirectResult->setPath('career/index/index');
        return $redirectResult;
    }

    /**
     * @return array
     */
    protected function getCsvHeader()
    {
        return [
            'entity_id' => __('Entity ID'),
            'name' => __('Name'),
            'post' => __('Post'),
            'city' => __('City'),
            'age' => __('Age'),
            'qualification' => __('Qualification'),
            'experience' => __('Experience'),
            'email' => __('Email'),
            'mobile' => __('Mobile'),
            'remark' => __('Remark'),
            'cv_file' => __('CV File'),
            'created_at' => __('Created At'),
            'updated_at' => __('Updated At'),
        ];
    }
}

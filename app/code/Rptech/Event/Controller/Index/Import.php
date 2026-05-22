<?php

namespace Rptech\Event\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

class Import extends \Magento\Framework\App\Action\Action
{
    const UPLOAD_DIR = "event/images";
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $_driverFile;
    /**
     * @var \Magento\Framework\File\Csv
     */
    private $_csv;
    /**
     * @var \Magento\Framework\Filesystem
     */
    private $_filesystem;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $_logger;
    /**
     * @var \Rptech\Event\Model\EventFactory
     */
    private $eventFactory;
    /**
     * @var \Rptech\Event\Model\EventImageFactory
     */
    private $eventImageFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\Filesystem $filesystem,
        \Psr\Log\LoggerInterface $logger,
        \Rptech\Event\Model\EventFactory $eventFactory,
        \Rptech\Event\Model\EventImageFactory $eventImageFactory

    )
    {
        $this->_driverFile = $driverFile;
        $this->_csv = $csv;
        $this->_filesystem = $filesystem;
        $this->_logger = $logger;
        $this->eventFactory = $eventFactory;
        $this->eventImageFactory = $eventImageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $csvName = "rptech_events_data.csv";
            $mediaDirectory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $destinationPath = $mediaDirectory->getAbsolutePath(self::UPLOAD_DIR);

            $csvFile = $destinationPath . "/" . $csvName;
            if ($this->_driverFile->isExists($csvFile)) {
                $this->_csv->setDelimiter(",");
                $data = $this->modifyCsvData($this->_csv->getData($csvFile));
//                echo '<pre>'; print_r($data); echo '</pre>'; die('endhere');
                foreach ($data as $row) {
                    $this->saveEvent($row);
                    die('stopped here');
                }
            }

        } catch (IOExceptionInterface $ex) {

            $this->_logger->error($ex->getMessage());
        } catch (\Exception $ex) {

            $this->_logger->error($ex->getMessage());
        }
        die('end here');
    }

    /**
     * @param $rows
     * @return array
     */
    private function modifyCsvData($rows)
    {
        $header = array_shift($rows);
        $csv = array();
        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
        }
        return $csv;
    }

    public function saveEvent($data){
        /**
         * @var \Rptech\Event\Model\Event $event
         * @var \Rptech\Event\Model\EventImage $event
         */
        $image = [];
        $event = $this->eventFactory->create();
        $event->setTitle($data['title']);
        $event->setDescription($data['description']);
        $event->setCity($data['city']);
        $event->setIsActive($data['is_active']);
        $event->setCreatedAt($data['created_at']);
        $event->setUpdatedAt($data['updated_at']);
        $event->save();

        for ($i = 1; $i <= 10; $i++) {
            if (!empty($data['event_image_'.$i])) {
                array_push($image, $data['event_image_'.$i]);
            }
        }

        if (!empty($image)) {
            foreach ($image as $img) {
                $imageName = $this->getImageName($img);
                $eventImage = $this->eventImageFactory->create();
                $eventImage->setEventEntityId($event->getEntityId());
                $eventImage->setImage($imageName);
                $result = $eventImage->save();
                if ($result) {
                    $this->moveImageToDirectory($img);
                }

            }
        }
        return "Data Saved Successfully";
    }

    /**
     * @param $url
     * Get URL and return the last string from URL
     * @return mixed|string
     */
    public function getImageName ($url) {
        return array_slice(explode('/', $url), -1)[0];
    }

    /**
     * @param $data
     */
    public function moveImageToDirectory($data){
        $mediaDirectory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $destinationPath = $mediaDirectory->getAbsolutePath(self::UPLOAD_DIR);
        $imgName = $this->getImageName($data);
        $ch = curl_init($data);
        $fp = fopen($destinationPath.'/'.$imgName, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return true;
    }
}
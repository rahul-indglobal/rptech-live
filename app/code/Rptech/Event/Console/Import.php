<?php

namespace Rptech\Event\Console;

use Magento\Framework\App\Area;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Import extends Command {

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
    /**
     * @var State
     */
    private $_state;

    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\Filesystem $filesystem,
        \Psr\Log\LoggerInterface $logger,
        \Rptech\Event\Model\EventFactory $eventFactory,
        \Rptech\Event\Model\EventImageFactory $eventImageFactory,
        State $state
    )
    {
        $this->_driverFile = $driverFile;
        $this->_csv = $csv;
        $this->_filesystem = $filesystem;
        $this->_logger = $logger;
        $this->eventFactory = $eventFactory;
        $this->eventImageFactory = $eventImageFactory;
        $this->_state = $state;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setName('events:import');
        $this->setDescription('Command to import the data from CSV');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checkAndSetAreaCode();
        try {
            $csvName = "rptech_events_data.csv";
            $mediaDirectory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $destinationPath = $mediaDirectory->getAbsolutePath(self::UPLOAD_DIR);

            $csvFile = $destinationPath . "/" . $csvName;
            if ($this->_driverFile->isExists($csvFile)) {
                $this->_csv->setDelimiter(",");
                $data = $this->modifyCsvData($this->_csv->getData($csvFile));
                foreach ($data as $row) {
                    $this->saveEvent($row);
                    $output->writeln("Event Data Saved for ".$row['entity_id']);
                }
            }
        }catch (\Exception $ex){
            $output->writeln("Error  " . $ex->getMessage());
        }
        return $this;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkAndSetAreaCode(){
        try {
            $this->_state->getAreaCode();
        }
        catch (\Magento\Framework\Exception\LocalizedException $e) {
            if ($e->getMessage() == 'Area code is not set') {
                $this->_state->setAreaCode(Area::AREA_ADMINHTML);
            }
        }
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
        $event->setCreatedAt(date("Y-m-d H:i:s", strtotime($data['created_at'])));
        $event->setUpdatedAt(date("Y-m-d H:i:s", strtotime($data['updated_at'])));
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
        return true;
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
<?php

namespace Rptech\Media\Console;

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
     * @var \Rptech\Media\Model\MediaFactory
     */
    private $mediaFactory;
    /**
    /**
     * @var State
     */
    private $_state;

    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\Filesystem $filesystem,
        \Psr\Log\LoggerInterface $logger,
        \Rptech\Media\Model\MediaFactory $mediaFactory,
        State $state
    )
    {
        $this->_driverFile = $driverFile;
        $this->_csv = $csv;
        $this->_filesystem = $filesystem;
        $this->_logger = $logger;
        $this->mediaFactory = $mediaFactory;
        $this->_state = $state;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setName('media:import');
        $this->setDescription('Command to import the media data from CSV');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checkAndSetAreaCode();
        try {
            $csvName = "rptech_media_data.csv";
            $mediaDirectory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $destinationPath = $mediaDirectory->getAbsolutePath(self::UPLOAD_DIR);

            $csvFile = $destinationPath . "/" . $csvName;
            if ($this->_driverFile->isExists($csvFile)) {
                $this->_csv->setDelimiter(",");
                $data = $this->modifyCsvData($this->_csv->getData($csvFile));
                foreach ($data as $row) {
                    $this->saveMedia($row);
                    $output->writeln("Media Data Saved for ".$row['title']);
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

    public function saveMedia($data){
        /**
         * @var \Rptech\Media\Model\Media $media
         */
        $media = $this->mediaFactory->create();
        $media->setTitle($data['title']);
        $media->setContent($data['content']);
        $media->setIsActive($data['is_active']);
        $media->setPublishDate(date("Y-m-d H:i:s", strtotime($data['publish_date'])));
        $media->setCreatedAt(date("Y-m-d H:i:s", strtotime($data['publish_date'])));
        $media->setUpdatedAt(date("Y-m-d H:i:s", strtotime($data['created_at'])));
        $media->save();
        return true;
    }

}
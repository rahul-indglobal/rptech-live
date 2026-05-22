<?php
/**
 * Grid Logger Handler.
 * @category  Manageawards_Awards 
 * @package   Manageawards_Awards
 * @author    Lalita Rajput
 */

namespace Manageawards\Awards\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level.
     *
     * @var int
     */
    public $loggerType = Logger::INFO;

    /**
     * File name.
     *
     * @var string
     */
    public $fileName = '/var/log/awards.log';
}

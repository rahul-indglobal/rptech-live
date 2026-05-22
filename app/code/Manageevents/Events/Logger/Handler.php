<?php
/**
 * Grid Logger Handler.
 * @category  Manageevents_Events 
 * @package   Manageevents_Events
 * @author    Lalita Rajput
 */

namespace Manageevents\Events\Logger;

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
    public $fileName = '/var/log/events.log';
}

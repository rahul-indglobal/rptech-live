<?php
/**
 * Grid Logger Handler.
 * @category  Leaders 
 * @package   Manageleaders_Leaders
 * @author    Lalita Rajput
 */

namespace Manageleaders\Leaders\Logger;

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
    public $fileName = '/var/log/leaders.log';
}

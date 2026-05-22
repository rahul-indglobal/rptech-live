<?php
/**
 * Grid Logger Handler.
 * @category  Managemedia_Rpmedia 
 * @package   Managemedia_Rpmedia
 * @author    Lalita Rajput
 */

namespace Managemedia\Rpmedia\Logger;

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
    public $fileName = '/var/log/rpmedia.log';
}

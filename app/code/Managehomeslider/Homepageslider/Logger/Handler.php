<?php
/**
 * Grid Logger Handler.
 * @category  Managehomeslider_Homepageslider 
 * @package   Managehomeslider_Homepageslider
 * @author    Lalita Rajput
 */

namespace Managehomeslider\Homepageslider\Logger;

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
    public $fileName = '/var/log/homepageslider.log';
}

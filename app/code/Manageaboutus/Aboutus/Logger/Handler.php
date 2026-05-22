<?php
/**
 * Manageaboutus_Aboutus Logger Handler.
 * @category  Manageaboutus_Aboutus 
 * @package   Manageaboutus_Aboutus
 * @author    Lalita Rajput
 */

namespace Manageaboutus\Aboutus\Logger;

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
    public $fileName = '/var/log/aboutus.log';
}

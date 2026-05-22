<?php
/**
 * Manageewastecontent_Ewastecontent Logger Handler.
 * @category  Manageewastecontent_Ewastecontent 
 * @package   Manageewastecontent_Ewastecontent
 * @author    Lalita Rajput
 */

namespace Manageewastecontent\Ewastecontent\Logger;

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
    public $fileName = '/var/log/ewaste.log';
}

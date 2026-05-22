<?php
/**
 * Grid Logger Handler.
 * @category  faq 
 * @package   Managefaq_Faq
 * @author    Lalita Rajput
 */

namespace Managefaq\Faq\Logger;

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
    public $fileName = '/var/log/faq.log';
}

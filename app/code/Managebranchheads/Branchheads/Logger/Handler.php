<?php
/**
 * Grid Logger Handler.
 * @category  Brancheads 
 * @package   Managebranchheads_Branchheads
 * @author    Lalita Rajput
 */

namespace Managebranchheads\Branchheads\Logger;

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
    public $fileName = '/var/log/branchhead.log';
}

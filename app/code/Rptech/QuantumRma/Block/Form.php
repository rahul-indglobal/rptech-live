<?php
namespace Rptech\QuantumRma\Block;

use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\View\Element\Template;

class Form extends Template
{
    /**
     * @var SessionManagerInterface
     */
    protected $session;

    /**
     * @param Template\Context $context
     * @param SessionManagerInterface $session
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        SessionManagerInterface $session,
        array $data = []

    ) {
        $this->session = $session;
        parent::__construct($context, $data);
    }

    public function getActionUrl()
	{
		return $this->getUrl('quantum-support/index/submit');
	}

    /**
     * @return null
     */
    public function getSuccessMessage()
    {
        //$this->session->start();
        $message = $this->session->getQuantumSuccessMessage();
        if (!$message || empty($message)) {
            return null;
        }
        $this->session->unsQuantumSuccessMessage();
        return $message;
    }
}
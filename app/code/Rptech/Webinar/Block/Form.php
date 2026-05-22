<?php
namespace Rptech\Webinar\Block;

use Magento\Framework\View\Element\Template;

class Form extends Template
{
	public function getActionUrl()
	{
		return $this->getUrl('nvidia-webinar/index/submit');
	}
}
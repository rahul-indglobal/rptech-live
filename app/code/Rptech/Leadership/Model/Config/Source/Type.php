<?php
namespace Rptech\Leadership\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Type implements OptionSourceInterface
{
	/**
	 * Return array of options as value-label pairs
	 */
	public function toOptionArray()
	{
		return [
			['value' => 'directors', 'label' => __('Board of Directors')],
			['value' => 'senior_management', 'label' => __('Key Managerial Personnel and Senior Management')],
		];
	}

	/**
	 * Return options as value => label (for UI rendering)
	 */
	public function getOptionArray()
	{
		return [
			'directors' => __('Board of Directors'),
			'senior_management' => __('Key Managerial Personnel and Senior Management'),
		];
	}
}

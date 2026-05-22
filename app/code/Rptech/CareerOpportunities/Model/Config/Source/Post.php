<?php

namespace Rptech\CareerOpportunities\Model\Config\Source;

/**
 * Class Type
 * @package Rptech\CareerOpportunities\Model\Config\Source
 */
class Post implements \Magento\Framework\Data\OptionSourceInterface
{
    const TYPE_MARKETING = 'marketing';
    const TYPE_SALES = 'sales';
    const TYPE_FINANCE = 'finance';
    const TYPE_OTHER = 'other';

    /**
     * Retrieve options array.
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];
        $result[] = [
            "value" => "",
            "label" => __("Please Select")
        ];
        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }
        return $result;
    }

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::TYPE_MARKETING => __("Marketing"),
            self::TYPE_SALES => __("Sales"),
            self::TYPE_FINANCE => __("Finance"),
            self::TYPE_OTHER => __("Other"),
        ];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }
}
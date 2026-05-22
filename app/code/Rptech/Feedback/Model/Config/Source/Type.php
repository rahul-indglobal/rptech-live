<?php

namespace Rptech\Feedback\Model\Config\Source;

/**
 * Class Type
 * @package Rptech\Feedback\Model\Config\Source
 */
class Type implements \Magento\Framework\Data\OptionSourceInterface
{
    const TYPE_FEEDBACK = 'feedback';
    const TYPE_ENQUIRY = 'enquiry';

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
            self::TYPE_FEEDBACK => __("Feedback"),
            self::TYPE_ENQUIRY => __("Enquiry"),
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
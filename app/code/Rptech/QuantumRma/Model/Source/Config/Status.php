<?php

namespace Rptech\QuantumRma\Model\Source\Config;

class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    const TYPE_PENDING = 'pending';
    const TYPE_COMPLETED = 'completed';
    const TYPE_CANCELED = 'canceled';
    const TYPE_REFUNDED = 'refunded';

    /**
     * Retrieve options array.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];

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
            self::TYPE_PENDING => __("Pending"),
            self::TYPE_COMPLETED => __("Completed"),
            self::TYPE_CANCELED => __("Canceled"),
            self::TYPE_REFUNDED => __("Refunded"),
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
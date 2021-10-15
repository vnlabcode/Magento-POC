<?php
namespace Rokanthemes\Themeoption\Model\Config;

class Fontfamilyprice implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'default', 'label' => __('Default')], 
            ['value' => 'google', 'label' => __('Google Fonts')]
        ];
    }

    public function toArray()
    {
        return [
            'default' => __('Default'), 
            'google' => __('Google Fonts')
        ];
    }
}

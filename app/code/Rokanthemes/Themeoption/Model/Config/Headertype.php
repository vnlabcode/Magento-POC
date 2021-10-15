<?php
namespace Rokanthemes\Themeoption\Model\Config;

class Headertype implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'header_funiture_01', 'label' => __('Header 01')],
            ['value' => 'header_funiture_02', 'label' => __('Header 02')],
            ['value' => 'header_funiture_03', 'label' => __('Header 03')],
            ['value' => 'header_funiture_04', 'label' => __('Header 04')],
            ['value' => 'header_funiture_05', 'label' => __('Header 05')],
            ['value' => 'header_funiture_06', 'label' => __('Header 06')],
			['value' => 'header_funiture_07', 'label' => __('Header 07')],
			['value' => 'header_funiture_08', 'label' => __('Header 08')],
			['value' => 'header_funiture_09', 'label' => __('Header 09')],
			['value' => 'header_funiture_09', 'label' => __('Header 09')],
			['value' => 'header_funiture_10', 'label' => __('Header 10')],
			['value' => 'header_funiture_11', 'label' => __('Header 11')],
			['value' => 'header_funiture_12', 'label' => __('Header 12')]
        ];
    }

    public function toArray()
    {
        return [
            'header_funiture_01' => __('Header 01'),
            'header_funiture_02' => __('Header 02'),
            'header_funiture_03' => __('Header 03'),
            'header_funiture_04' => __('Header 04'),
            'header_funiture_05' => __('Header 05'),
            'header_funiture_06' => __('Header 06'),
			'header_funiture_07' => __('Header 07'),
			'header_funiture_08' => __('Header 08'),
			'header_funiture_09' => __('Header 09'),
			'header_funiture_10' => __('Header 10'),
			'header_funiture_11' => __('Header 11'),
			'header_funiture_12' => __('Header 12')
        ];
    }
}
<?php
namespace Rokanthemes\Themeoption\Model\Config;

class Footertype implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'footer_funiture_01', 'label' => __('Footer 01')],
            ['value' => 'footer_funiture_02', 'label' => __('Footer 02')],
            ['value' => 'footer_funiture_03', 'label' => __('Footer 03')],
            ['value' => 'footer_funiture_04', 'label' => __('Footer 04')],
            ['value' => 'footer_funiture_05', 'label' => __('Footer 05')],
            ['value' => 'footer_funiture_06', 'label' => __('Footer 06')],
			['value' => 'footer_funiture_07', 'label' => __('Footer 07')],
			['value' => 'footer_funiture_08', 'label' => __('Footer 08')],
			['value' => 'footer_funiture_09', 'label' => __('Footer 09')],
			['value' => 'footer_funiture_10', 'label' => __('Footer 10')],
			['value' => 'footer_funiture_11', 'label' => __('Footer 11')],
			['value' => 'footer_funiture_12', 'label' => __('Footer 12')]
        ];
    }

    public function toArray()
    {
        return [
            'footer_funiture_01' => __('Footer 01'),
            'footer_funiture_02' => __('Footer 02'),
            'footer_funiture_03' => __('Footer 03'),
            'footer_funiture_04' => __('Footer 04'),
            'footer_funiture_05' => __('Footer 05'),
            'footer_funiture_06' => __('Footer 06'),
			'footer_funiture_07' => __('Footer 07'),
			'footer_funiture_08' => __('Footer 08'),
			'footer_funiture_09' => __('Footer 09'),
			'footer_funiture_10' => __('Footer 10'),
			'footer_funiture_11' => __('Footer 11'),
			'footer_funiture_12' => __('Footer 12')
        ];
    }
}
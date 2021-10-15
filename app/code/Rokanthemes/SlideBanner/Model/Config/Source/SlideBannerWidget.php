<?php
namespace Rokanthemes\SlideBanner\Model\Config\Source;

class SlideBannerWidget extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\Option\ArrayInterface
{

    protected $_slideCollectionFactory;

    public function __construct(
        \Rokanthemes\SlideBanner\Model\ResourceModel\Slider\CollectionFactory $slideCollectionFactory

    ) {
        $this->_slideCollectionFactory = $slideCollectionFactory;
    }

    public function getSliderCollection()
    {
        $collection = $this->_slideCollectionFactory->create();

        return $collection;
    }

    public function toOptionArray()
    {
        $sliderCollection = $this->getSliderCollection();

        $values = [];

        $values[] = [
            'value' => 0, 'label' => __('Select Custom Slider')
        ];
        
        foreach ($sliderCollection as $slider) {
            $values[] = [
                'value' => $slider->getSliderId(), 'label' => $slider->getSliderTitle()
            ];
        }

        return $values;
    }
}

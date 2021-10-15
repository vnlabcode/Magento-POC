<?php
namespace Rokanthemes\SlideBanner\Block;

class Slider extends \Magento\Framework\View\Element\Template 
{
    protected $_filterProvider;
    protected $_sliderFactory;
    protected $_bannerFactory;
    protected $_storeManager;

   public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
        \Rokanthemes\SlideBanner\Model\SliderFactory $sliderFactory,
        \Rokanthemes\SlideBanner\Model\SlideFactory $slideFactory,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
	) {
   		$this->_filterProvider = $filterProvider;
        $this->_sliderFactory = $sliderFactory;
        $this->_bannerFactory = $slideFactory;
        $this->_storeManager = $storeManager;

		parent::__construct($context, $data);
	}

    protected function _beforeToHtml()
    {
        $sliderId = $this->getSliderId();
		if($this->getIdentifier()){
			$sliderId = $this->getIdentifier();
		}
        if ($sliderId && !$this->getTemplate()) {
			$this->setTemplate("Rokanthemes_SlideBanner::slider.phtml");
        }
        return parent::_beforeToHtml();
    }

    public function getSlider()
    {
        $sliderId = $this->getSliderId();
        if($sliderId){
            $slider = $this->_sliderFactory->create()->load($sliderId);
            if($slider->getId() && $slider->getSliderStatus()){
                return $slider;
            }
            return false;
        }
        return false;
    }

    public function getBannerCollection($slider_id)
    {
        $collection = $this->_bannerFactory->create()->getCollection();
        $collection->addFieldToFilter('slider_id', $slider_id);
        $collection->addFieldToFilter('slide_status', 1);
        $collection->setOrder('slide_position','ASC');
        return $collection;
    }

    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
    
    public function getContentText($html)
    {
        $html = $this->_filterProvider->getPageFilter()->filter($html);
        return $html;
    }
}

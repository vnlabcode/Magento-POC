<?php

namespace Rokanthemes\Themeoption\Helper;

class Themeconfig extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_storeManager;
    protected $cssFolder;
    protected $cssPath;
    protected $cssDir;
	protected $_themeoptionversion;
    
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
		\Rokanthemes\Themeoption\Model\Themeoptionversion $themeoptionversion,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        $base = BP;
        $this->cssFolder = 'rokanthemes/theme_option/';
        $this->cssPath = 'pub/media/'.$this->cssFolder;
        $this->cssDir = $base.'/'.$this->cssPath;
		$this->_themeoptionversion = $themeoptionversion;
        parent::__construct($context);
    }
    
    public function getBaseMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
    
    public function getConfigDir()
    {
        return $this->cssDir;
    }
    
    public function getThemeOption()
    {
		$getver = $this->_themeoptionversion->getLatestVersion();
        return $this->getBaseMediaUrl(). $this->cssFolder . 'custom_' . $this->_storeManager->getStore()->getCode() . '.css?v='.$getver;
    }
	public function isEnableStickyHeader()
	{
		if($this->scopeConfig->getValue('themeoption/header/sticky_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)){
			return 1;
		}
		else{
			return 0;
		}
	}
	
	public function isEnableFakeOrder()
	{
		if($this->scopeConfig->getValue('themeoption/fake_order/enable_f_o', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function getInfoFakeOrder($path)
	{
		return $this->scopeConfig->getValue('themeoption/fake_order/'.$path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
	
	public function getFooterLogo()
	{
		$logo = $this->scopeConfig->getValue('themeoption/footer/footer_logo', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		if($logo != ''){
			$folderName = \Rokanthemes\Themeoption\Model\Config\Footerlogo::UPLOAD_DIR;
			$path = $folderName . '/' .$logo;
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
			return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$path;
		}
		else{
			return '';
		}
	}
	
	public function getStickyLogoHeader()
	{
		$logo = $this->scopeConfig->getValue('themeoption/header/sticky_logo', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		if($logo != ''){
			$folderName = \Rokanthemes\Themeoption\Model\Config\Stickylogo::UPLOAD_DIR;
			$path = $folderName . '/' .$logo;
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
			return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$path;
		}
		else{
			return '';
		}
	}
	
	public function getIconlazyLoadUrl()
	{
		$icon = $this->scopeConfig->getValue('themeoption/general/icon_lazyload', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		if($icon != ''){
			$folderName = \Rokanthemes\Themeoption\Model\Config\IconlazyLoad::UPLOAD_DIR;
			$path = $folderName . '/' .$icon;
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
			return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$path;
		}
		else{
			return '';
		}
	}
}

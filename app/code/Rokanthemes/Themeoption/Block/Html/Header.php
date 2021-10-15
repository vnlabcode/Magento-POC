<?php

namespace Rokanthemes\Themeoption\Block\Html;

class Header extends \Magento\Framework\View\Element\Template
{

    public function _toHtml()
    {
        $header_config = $this->_scopeConfig->getValue(
            'themeoption/header/select_header_type',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if($header_config && $header_config != ''){
            $this->setTemplate('Magento_Theme::html/headers/'.$header_config.'.phtml');
        }
        else{
            $this->setTemplate('Magento_Theme::html/header_custom.phtml');
        }
        
        return parent::_toHtml();
    }
}

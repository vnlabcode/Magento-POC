<?php

namespace Rokanthemes\Themeoption\Block\Html;

class Footer extends \Magento\Framework\View\Element\Template
{

    public function _toHtml()
    {
        $header_config = $this->_scopeConfig->getValue(
            'themeoption/footer/select_footer_type',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if($header_config && $header_config != ''){
            $this->setTemplate('Magento_Theme::html/footers/'.$header_config.'.phtml');
        }
        else{
            $this->setTemplate('Magento_Theme::html/footer_custom.phtml');
        }
        
        return parent::_toHtml();
    }
}

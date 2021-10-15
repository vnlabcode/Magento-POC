<?php
namespace Rokanthemes\Themeoption\Block\Html;

class Notices extends \Magento\Framework\View\Element\Template
{
    protected $_verifypurchasecode;

    public function __construct(
        \Rokanthemes\Themeoption\Helper\Verifypurchasecode $verifypurchasecode,
        \Magento\Framework\View\Element\Template\Context $context, 
        array $data = []
    )
    {
        $this->_verifypurchasecode = $verifypurchasecode;
        parent::__construct($context, $data);
    }

    public function displayNoticeActivationPurchaseCode()
    {
        return !$this->_verifypurchasecode->checkEnvatoPurchaseCode();;
    }
}

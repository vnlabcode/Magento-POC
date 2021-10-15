<?php
namespace Rokanthemes\OptimizeSpeed\Block\Adminhtml\Config\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray as FormAbstractFieldArray;
use Magento\Framework\Data\Form\Element\AbstractElement;

class UrlImage extends FormAbstractFieldArray
{
    protected function _construct()
    {
        $this->_addAfter = false;
        $this->addColumn('expression', ['label' => __('Url Image Link')]);
        parent::_construct();
    }
}


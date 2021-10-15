<?php
namespace Rokanthemes\ProductTab\Block\Adminhtml\Widget;
Class Types extends \Magento\Backend\Block\Template{
    protected $_elementFactory;
    protected $_helper;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        \Rokanthemes\ProductTab\Helper\Data $helper,
        array $data = []
    ) {
        $this->_elementFactory = $elementFactory;
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }
    /**
     * Prepare chooser element HTML
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element Form Element
     * @return \Magento\Framework\Data\Form\Element\AbstractElement
     */
    public function prepareElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $data = $element->getData();
        $values = [];
        foreach($this->_helper->getAllOptionTypes() as $type => $label)
        {
            $values[] = ['value' => $type, 'label' => $label];
        }
        $data['values'] = $values;
        $input = $this->_elementFactory->create("multiselect", ['data' => $data]);
        $input->setId($element->getId());
        $input->setForm($element->getForm());
        $input->setClass("widget-option input-textarea admin__control-text");
        if ($element->getRequired()) {
            $input->addClass('required-entry');
        }
        $url = $this->getUrl('producttab/populate/bestseller',['_current' => true]);
        $urlMostView = $this->getUrl('producttab/populate/bestseller');
        $html = __('Pls setup and run cronjob or Click link to populate data. ( Most Viewed you need to enable Report Viewed Mgt )');
        $html .= "<br/>
        <a href='$url' target='_blank'>" . __('Populate Best Seller') . "</a><br/>
        <a href='$urlMostView' target='_blank'>" . __('Populate Most Viewed') . "</a>
            ";
        $element->setData('after_element_html', $input->getElementHtml() . $html);
        return $element;
    }
}

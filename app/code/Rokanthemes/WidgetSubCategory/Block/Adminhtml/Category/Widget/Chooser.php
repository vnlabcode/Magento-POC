<?php
namespace Rokanthemes\WidgetSubCategory\Block\Adminhtml\Category\Widget;

class Chooser extends \Magento\Backend\Block\Template
{

    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $factoryElement;
    protected $categoryBlock;
    protected $_collectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        \Rokanthemes\WidgetSubCategory\Block\Adminhtml\Category\Form\Element\Category $categoryBlock,
        $data = []
    ) {
        $this->factoryElement = $elementFactory;
        $this->categoryBlock = $categoryBlock;
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
        $categoryHtml = $this->getLayout()->createBlock('\Rokanthemes\WidgetSubCategory\Block\Adminhtml\Category\Form\Element\Category')->setValue($element->getValue())->toHtml();
        $element->setData('after_element_html', $categoryHtml);
        return $element;
    }
}

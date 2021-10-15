<?php
namespace Rokanthemes\ProductTab\Block\Widget;

/**
 * Catalog Products List widget block
 * Class ProductsList
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Tabs extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = "widget/tabs.phtml";
    protected $_helper;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                \Rokanthemes\ProductTab\Helper\Data $helper,
                                array $data = []
    ){
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }
    public function getTabs()
    {
        if($types = $this->getData('types'))
        {
            $types = explode(',' , $types);
            $result = [];
            foreach($types as $type)
            {
                $result[] = ['value' => $type, 'label' => $this->_helper->getTypeLabel($type)];
            }
            return $result;
        }
        return [];
    }
    public function getSettingStatus($group = 'general')
    {
        return $this->_scopeConfig->getValue('producttab/' . $group . '/enabled');
    }
    public function getCacheKeyInfo()
    {
        return array_merge(
            parent::getCacheKeyInfo(),
            [
                'category_tab',
                $this->getData('category_ids'),
                $this->getData('limit'),
                $this->getData('sorting'),
                $this->getData('mode_view'),
                $this->serializer->serialize($this->getRequest()->getParams())
            ]
        );
    }

}
?>

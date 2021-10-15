<?php
namespace Rokanthemes\CategoryTab\Block\Widget;

/**
 * Catalog Products List widget block
 * Class ProductsList
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CateTabs extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = "widget/category/tabs.phtml";
    protected $_catCollectionFactory;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory,
                                array $data = []
    ){
        $this->_catCollectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }
    public function getCategories()
    {
        $category_ids = explode(',', $this->getData('category_ids'));
        $collection = $this->_catCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('entity_id', ['in'=>$category_ids]);
        return $collection;
    }
    public function getSettingStatus($group = 'general')
    {
        return $this->_scopeConfig->getValue('categorytab/' . $group . '/enabled');
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
    public function getImageSrcCategory($path)
    {
        if($path != ''){
            $trim_pub = explode("media", $path);
            $url_fixed = $path;
            if(is_array($trim_pub) && count($trim_pub) > 0){
                $path_media = ltrim(end($trim_pub), '/');
                $mediaUrl = $this ->_storeManager-> getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
                $url_fixed = $mediaUrl.$path_media;
            }
            return $url_fixed;
        }
        return '';
    }
    public function getImageBackGround()
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
        if($this->getData('image_background'))
        {
            $trim_pub = explode("media", $this->getData('image_background'));
            $url_fixed = $this->getData('image_background');
            if(is_array($trim_pub) && count($trim_pub) > 0){
                $path_media = ltrim(end($trim_pub), '/');
                $mediaUrl = $this ->_storeManager-> getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
                $url_fixed = $mediaUrl.$path_media;
            }
            $html = '<img src="' . $url_fixed . '" class="background_block" alt="" />'; 
            return $html;
        }
        return '';
    }
}
?>

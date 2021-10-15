<?php
namespace Rokanthemes\WidgetSubCategory\Block\Widget;

use Magento\Framework\App\RequestInterface;
/**
 * Catalog Products List widget block
 * Class ProductsList
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CateTabs extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = "widget/category/tabs.phtml";
    protected $_catCollectionFactory;
    protected $_filterList;
    protected $layer;
    /**
     * Filter factory
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory,
                                \Magento\Framework\ObjectManagerInterface $objectManager,
                                \Magento\Catalog\Model\Layer\Resolver $layerResolver,
                                RequestInterface $request,
                                array $data = []
    ){
        $this->_catCollectionFactory = $collectionFactory;
        $this->objectManager = $objectManager;
        $this->layer = $layerResolver;
        $this->request = $request;
        parent::__construct($context, $data);
        if($this->getData('owl_center') == 'true')
        {
            $this->setData('owl_loop', true);
        }
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
        return $this->_scopeConfig->getValue('widgetsubcategory/' . $group . '/enabled');
    }
    public function getCacheKeyInfo()
    {
        return array_merge(
            parent::getCacheKeyInfo(),
            [
                'widget_sub_category',
                $this->getData('category_ids'),
                $this->getData('tab_postions'),
                $this->serializer->serialize($this->getRequest()->getParams())
            ]
        );
    }
    public function getSubCategories($category)
    {
        $collection = $this->_catCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('entity_id', ['in'=>$category->getChildren()]);
        return $collection;
    }
    public function getSubCategoriesCount($category)
    {
        $request = $this->request;
        $request = clone $request;
        $layer = $this->objectManager->create(\Magento\Catalog\Model\Layer\Category::class);
        if(!$category)
        {
            $category = $this->storeManager->getStore()->getRootCategoryId();
        }
        if ($category) {
            $layer->setCurrentCategory($category);
        }
        $categoryFilter = $this->objectManager->create(\Magento\Catalog\Model\Layer\Filter\Category::class, ['layer' => $layer]);
        $categoryFilter->setData('layer', $layer);
        $categoryFilter->apply($request);
        $layer->apply();
        $items = [];
        foreach ($categoryFilter->getItems() as $item)
        {
            //echo $item->getValue(); die('bbb');
            $items[$item->getValue()] = $item->getCount();
        }
        return $items;
    }
    public function getSubCategoriesHtml($category, $level = 0)
    {
        $level++;
        if($this->getData('max_level') == '' || (!$this->getData('max_level') && $this->getData('max_level') != 0))
        {
            return '';
        }
        if($this->getData('max_level') && $this->getData('max_level') != 0 && $this->getData('max_level') < $level )
        {
            return '';
        }
        if(!$category->getChildren())
        {
            return '';
        }
        $html = '<ul>';
        $categoriesCount = [];
        if($this->getData('show_product_count'))
        {
            $categoriesCount = $this->getSubCategoriesCount($category);
        }
        foreach ($this->getSubCategories($category) as $subCategory) {
            $html .= '<li>';
                $html .= '<a href="'. $subCategory->getUrl() .'">'. $subCategory->getName() ;
                if(isset($categoriesCount[$subCategory->getId()]))
                {
                    $html .= '('. $categoriesCount[$subCategory->getId()] . ')';
                }
                $html .= '</a>';
                $html .= $this->getSubCategoriesHtml($subCategory, $level);
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    public function getImageHtml($category)
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
        if($type = $this->getData('show_category_image'))
        {   $classImage = $this->getData('show_category_image_type');
            if($img = $category->getData($type))
            {
                $trim_pub = explode("category/", $img);
                $url_fixed = $img;
                if(is_array($trim_pub) && count($trim_pub) > 0){
                    $path_media = ltrim(end($trim_pub), '/');
                    $mediaUrl = $this ->_storeManager-> getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
                    $url_fixed = $mediaUrl.'catalog/category/'.$path_media;
                }
                
                return '<img alt="'. $category->getName() .'" class="'. $classImage .'" src="'.$url_fixed .'" />';
            }
        }
        return '';
    }
    public function getImageBackGround()
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
        if($this->getData('image_background'))
        {
            $html = '<img style="position:absolute; z-index:0;" src="' . $mediaUrl . $this->getData('image_background') . '" class="background_block" alt="" />';
            return $html;
        }
        return '';
    }
    public function getStyleBackground()
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
        if($this->getData('image_background'))
        {
            $html = 'style="background-image: url(' . $mediaUrl . $this->getData('image_background') . ');"';
            return $html;
        }
        return '';
    }
    public function getCountProductCategory($category)
    {
        if(!$this->getData('show_product_count'))
            return '';
        $request = $this->request;
        $request = clone $request;
        $layer = $this->objectManager->create(\Magento\Catalog\Model\Layer\Category::class);
        if(!$category)
        {
            $category = $this->storeManager->getStore()->getRootCategoryId();
        }
        if ($category) {
            $layer->setCurrentCategory($category);
        }
        $categoryFilter = $this->objectManager->create(\Magento\Catalog\Model\Layer\Filter\Category::class, ['layer' => $layer]);
        $categoryFilter->setData('layer', $layer);
        $categoryFilter->apply($request);
        $layer->apply();
       return '<span class="count">(' . $layer->getProductCollection()->getSize() . ' '.__('items').')</span>';
    }
}
?>

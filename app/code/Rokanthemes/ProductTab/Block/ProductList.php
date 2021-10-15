<?php
namespace Rokanthemes\ProductTab\Block;


use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\ProductList\Toolbar;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;
use Rokanthemes\ProductTab\Helper\Data as HelperModule;
/**
 * Catalog Products List widget block
 * Class ProductsList
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductList extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * Default toolbar block name
     *
     * @var string
     */
    protected $_defaultToolbarBlock = Toolbar::class;

    /**
     * Product Collection
     *
     * @var AbstractCollection
     */
    protected $_productCollection;

    /**
     * Catalog layer
     *
     * @var Layer
     */
    protected $_catalogLayer;

    /**
     * @var PostHelper
     */
    protected $_postDataHelper;

    /**
     * @var Data
     */
    protected $urlHelper;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Data $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_date = $date;
        parent::__construct($context, $postDataHelper, $layerResolver,  $categoryRepository,  $urlHelper, $data);
    }
    /**
     * Retrieve loaded product collection
     *
     * The goal of this method is to choose whether the existing collection should be returned
     * or a new one should be initialized.
     *
     * It is not just a caching logic, but also is a real logical check
     * because there are two ways how collection may be stored inside the block:
     *   - Product collection may be passed externally by 'setCollection' method
     *   - Product collection may be requested internally from the current Catalog Layer.
     *
     * And this method will return collection anyway,
     * even when it did not pass externally and therefore isn't cached yet
     *
     * @return AbstractCollection
     */
    protected function _getProductCollection()
    {
        $type = $this->getRequest()->getParam('type');
        switch ($type)
        {
            case HelperModule::TYPE_BEST_SELLER:
                return $this->_getBestSellerProductCollection();
                break;
            case HelperModule::TYPE_FEATURER:
                return $this->_getFeaturedProductCollection();
                break;
            case HelperModule::TYPE_MOST_VIEWED:
                return $this->_getMostViewProductCollection();
                break;
            case HelperModule::TYPE_NEW:
                return $this->_getRecentlyAddedProductsCollection();
                break;
            case HelperModule::TYPE_TOP_RATE:
                return $this->_getTopRateProductCollection();
                break;
            case HelperModule::TYPE_ON_SALE:
                return $this->_getOnSaleProductCollection();
                break;
        }
        return $this->_getRandomProductCollection();
    }
    /**
     * Prepare collection for recent product list
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|Object|\Magento\Framework\Data\Collection
     */
    protected function _getRecentlyAddedProductsCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter('new', 1)
            ->addAttributeToSort('created_at', 'desc')
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getCurrentPage());
        return $collection;
    }
    protected function _getBestSellerProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $tableMostViewed = $collection->getTable('rokanthemes_sorting_bestseller');
        $tableAlias = $collection::MAIN_TABLE_ALIAS;
        $storeId = $this->_storeManager->getStore()->getId();
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getPageSize());
        $collection->getSelect()->joinLeft(
            ['bestseller' => $tableMostViewed],
            "$tableAlias.entity_id = bestseller.product_id and bestseller.store_id = $storeId",
            ["bestseller.bestseller"]
        );
        $collection->getSelect()->order('bestseller desc');
        return $collection;
    }
    protected function _getFeaturedProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter('is_featured', 1)
            ->setPageSize($this->getPageSize());
        return $collection;
    }
    protected function _getMostViewProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $tableMostViewed = $collection->getTable('rokanthemes_sorting_most_viewed');
        $tableAlias = $collection::MAIN_TABLE_ALIAS;
        $storeId = $this->_storeManager->getStore()->getId();
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getPageSize());
        $collection->getSelect()->joinLeft(
            ['mostviewed' => $tableMostViewed],
            "$tableAlias.entity_id = mostviewed.product_id and mostviewed.store_id = $storeId",
            ["mostviewed.viewed"]
        );
        $collection->getSelect()->order('viewed desc');
        return $collection;
    }
    protected function _getTopRateProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $tableReview = $collection->getTable('review_entity_summary');
        $tableAlias = $collection::MAIN_TABLE_ALIAS;
        $storeId = $this->_storeManager->getStore()->getId();
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getPageSize());
        $collection->getSelect()->joinLeft(
            ['top_review' => $tableReview],
        "$tableAlias.entity_id = top_review.entity_pk_value and top_review.store_id = $storeId and top_review.entity_type = 1",
            ["top_review.rating_summary", 'top_review.reviews_count']
        );
        $collection->getSelect()->order('rating_summary desc');
        $collection->getSelect()->order('reviews_count desc');
        return $collection;
    }
    protected function _getOnSaleProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $date = $this->_date->gmtDate();
        $tableAlias = $collection::MAIN_TABLE_ALIAS;
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getPageSize());
        $collection->addAttributeToFilter('special_price', ['notnull'=> true]);
        //$collection->addAttributeToFilter('special_price', ['lt'=> new \Zend_Db_Expr("$tableAlias.price")]);
        $collection->addAttributeToFilter('special_from_date', [['lteq'=> $date],['null'=> true]]);
        $collection->addAttributeToFilter('special_to_date', [['gteq'=> $date],['null'=> true]]);
        return $collection;
    }
    protected function _getRandomProductCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getPageSize());
        $collection->getSelect()->order('RAND()');
        return $collection;
    }
}

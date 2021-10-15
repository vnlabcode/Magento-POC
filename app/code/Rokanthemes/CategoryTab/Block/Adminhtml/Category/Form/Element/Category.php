<?php
namespace Rokanthemes\CategoryTab\Block\Adminhtml\Category\Form\Element;

use Magento\Catalog\Model\Category as CategoryModel;
/**
 * Product form category field helper
 */
class Category extends \Magento\Backend\Block\Template
{

    protected $_template = 'Rokanthemes_CategoryTab::categories.phtml';
    protected $_collectionFactory;
    protected $_jsonHelper;
    public function __construct(\Magento\Backend\Block\Template\Context $context,
                                \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory,
                                \Magento\Framework\Json\Helper\Data $jsonHelper,
                                array $data = [])
    {
        $this->_collectionFactory = $collectionFactory;
        $this->_jsonHelper = $jsonHelper;
        parent::__construct($context, $data);
    }
    public function getSelectedValue()
    {
        $values = [];
        if($this->getValue() && is_array($this->getValue()))
        {
            $values = $this->getValue();
        }elseif($this->getValue())
        {
            $values = explode(',', $this->getValue());
        }
        return $this->_jsonHelper->jsonEncode($values);
    }
    public function getSelectedValues()
    {
        $values = [];
        if($this->getValue() && is_array($this->getValue()))
        {
            $values = $this->getValue();
        }elseif($this->getValue())
        {
            $values = explode(',', $this->getValue());
        }
        return $values;
    }
    /**
     * Retrieve categories tree
     *
     * @param string|null $filter
     * @return array
     * @throws LocalizedException
     * @since 101.0.0
     */
    public function getCategoriesTree($filter = null)
    {
        $storeId = 0;

        $categoriesTree = $this->retrieveCategoriesTree(
            $storeId,
            $this->retrieveShownCategoriesIds($storeId, (string) $filter)
        );
        return $this->_jsonHelper->jsonEncode($categoriesTree);
    }
    public function getCategories()
    {
        $storeId = 0;
        $collection = $this->_collectionFactory->create();
		$collection->addAttributeToFilter('level', array('gt'=>0));
        $collection->addAttributeToSelect(['name', 'is_active', 'parent_id'])
            ->setStoreId($storeId);
        return $collection;
    }
    public function getOptionsCategory()
    {
        $html = '';
        foreach($this->getCategories() as $cat)
        {
            $selected = '';
            if(in_array($cat->getId(), $this->getSelectedValues()))
            {
                $selected = 'selected="selected"';
            }
            $html .= '<option value="' . $cat->getId() . '" '. $selected .'>' . $cat->getName() . '</option>';
        }
        return $html;
    }
    /**
     * Retrieve filtered list of categories id.
     *
     * @param int $storeId
     * @param string $filter
     * @return array
     * @throws LocalizedException
     */
    private function retrieveShownCategoriesIds(int $storeId, string $filter)
    {
        /* @var $matchingNamesCollection \Magento\Catalog\Model\ResourceModel\Category\Collection */
        $matchingNamesCollection = $this->_collectionFactory->create();

        if (!empty($filter)) {
            $matchingNamesCollection->addAttributeToFilter(
                'name',
                ['like' => $this->dbHelper->addLikeEscape($filter, ['position' => 'any'])]
            );
        }

        $matchingNamesCollection->addAttributeToSelect('path')
            ->addAttributeToFilter('entity_id', ['neq' => CategoryModel::TREE_ROOT_ID])
            ->setStoreId($storeId);

        $shownCategoriesIds = [];

        /** @var \Magento\Catalog\Model\Category $category */
        foreach ($matchingNamesCollection as $category) {
            foreach (explode('/', $category->getPath()) as $parentId) {
                $shownCategoriesIds[$parentId] = 1;
            }
        }

        return $shownCategoriesIds;
    }
    /**
     * Retrieve tree of categories with attributes.
     *
     * @param int $storeId
     * @param array $shownCategoriesIds
     * @return array|null
     * @throws LocalizedException
     */
    private function retrieveCategoriesTree(int $storeId, array $shownCategoriesIds)
    {
        /* @var $collection \Magento\Catalog\Model\ResourceModel\Category\Collection */
        $collection = $this->_collectionFactory->create();

        $collection->addAttributeToFilter('entity_id', ['in' => array_keys($shownCategoriesIds)])
            ->addAttributeToSelect(['name', 'is_active', 'parent_id'])
            ->setStoreId($storeId);

        $categoryById = [
            CategoryModel::TREE_ROOT_ID => [
                'value' => CategoryModel::TREE_ROOT_ID,
                'optgroup' => null,
            ],
        ];

        foreach ($collection as $category) {
            foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                if (!isset($categoryById[$categoryId])) {
                    $categoryById[$categoryId] = ['value' => $categoryId];
                }
            }

            $categoryById[$category->getId()]['is_active'] = $category->getIsActive();
            $categoryById[$category->getId()]['label'] = $category->getName();
            $categoryById[$category->getId()]['__disableTmpl'] = true;
            $categoryById[$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
        }

        return $categoryById[CategoryModel::TREE_ROOT_ID]['optgroup'];
    }
}

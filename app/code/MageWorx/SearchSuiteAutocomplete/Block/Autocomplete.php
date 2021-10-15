<?php

namespace MageWorx\SearchSuiteAutocomplete\Block;

/**
 * Autocomplete class used for transport config data
 */
class Autocomplete extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \MageWorx\SearchSuiteAutocomplete\Helper\Data
     */
    protected $helperData;

	protected $_categoryHelper;
    /**
     * Autocomplete constructor.
     *
     * @param \MageWorx\SearchSuiteAutocomplete\Helper\Data $helperData
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \MageWorx\SearchSuiteAutocomplete\Helper\Data $helperData,
        \Magento\Framework\View\Element\Template\Context $context,
		\Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    ) {

        $this->helperData = $helperData;
		$this->_categoryHelper = $categoryHelper;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve search delay in milliseconds (500 by default)
     *
     * @return int
     */
    public function getSearchDelay()
    {
        return $this->helperData->getSearchDelay();
    }

    /**
     * Retrieve search action url
     *
     * @return string
     */
    public function getSearchUrl()
    {
        return $this->getUrl("mageworx_searchsuiteautocomplete/ajax/index");
    }
	
	public function getCategories()
    {
        return $this->_categoryHelper->getStoreCategories(true , false, true);
    }
}

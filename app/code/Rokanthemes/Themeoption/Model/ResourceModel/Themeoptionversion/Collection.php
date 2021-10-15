<?php

namespace Rokanthemes\Themeoption\Model\ResourceModel\Themeoptionversion;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'version_id';
	
	protected function _construct()
	{
		$this->_init('Rokanthemes\Themeoption\Model\Themeoptionversion', 'Rokanthemes\Themeoption\Model\ResourceModel\Themeoptionversion');
	}
}

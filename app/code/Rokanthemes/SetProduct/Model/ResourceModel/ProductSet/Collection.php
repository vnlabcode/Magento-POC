<?php
namespace Rokanthemes\SetProduct\Model\ResourceModel\ProductSet;

use Magento\Rule\Model\ResourceModel\Rule\Collection\AbstractCollection;
use Rokanthemes\SetProduct\Model\ResourceModel\ProductSet;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';  
    protected function _construct()
    {
        $this->_init(\Rokanthemes\SetProduct\Model\ProductSet::class, ProductSet::class);
    }
}

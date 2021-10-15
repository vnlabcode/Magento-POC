<?php
 
namespace Rokanthemes\SetProduct\Block\Adminhtml\ProductSet\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;
 
class Tabs extends WidgetTabs
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('productset_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Add Product Set'));
    }
}
<?php
 
namespace Rokanthemes\SetProduct\Block\Adminhtml;
 
use Magento\Backend\Block\Widget\Grid\Container;
 
class ProductSet extends Container
{
   /**
     * Constructor
     *
     * @return void
     */
	protected function _construct()
    {
		$this->_controller = 'adminhtml_productSet';
        $this->_blockGroup = 'Rokanthemes_SetProduct';
        $this->_headerText = __('Add Products Set');
        $this->_removeButtonLabel = __('Add Products Set');
        parent::_construct();
    }
}

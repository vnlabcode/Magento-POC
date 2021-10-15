<?php
 
namespace Rokanthemes\SetProduct\Block\Adminhtml\ProductSet;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Rokanthemes\SetProduct\Model\ProductSet;
 
class Edit extends Container
{
   /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
 
    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
 
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_productSet';
        $this->_blockGroup = 'Rokanthemes_SetProduct'; 
 
        parent::_construct();
		$this->buttonList->add(
            'save-and-continue',
            [
                'label'          => __('Save and Continue Edit'),
                'class'          => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event'  => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            -100
        );
    }
 
    /**
     * Retrieve text for header element depending on loaded news
     * 
     * @return string
     */
    public function getHeaderText()
    {
        $rule = $this->_coreRegistry->registry('rokanthemes_setproduct'); 
        if ($rule->getId()) {
            return __("Edit Labels '%1'", $this->escapeHtml($rule->getName()));
        }

        return __('Create New Item');
    }

	public function getSaveUrl()
    {
        return $this->getFormActionUrl();
    }
	
    public function getFormActionUrl()
    {
        $rule = $this->_coreRegistry->registry('rokanthemes_setproduct');

        if ($id = $rule->getId()) {
            return $this->getUrl('*/*/save', ['id' => $id]);
        }

        return parent::getFormActionUrl();
    }
}
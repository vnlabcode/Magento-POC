<?php
 
namespace Rokanthemes\SetProduct\Block\Adminhtml\ProductSet\Edit\Tab;

use IntlDateFormatter;
use Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Magento\Store\Model\System\Store;
use Rokanthemes\SetProduct\Model\ProductSet;
 
class Infopac extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_enableDisable;
    protected $_yesNo;
    public $systemStore;
    protected $_groupRepository;
    protected $_searchCriteriaBuilder;
    protected $_objectConverter;


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Config\Model\Config\Source\Enabledisable $enableDisable,
        \Magento\Config\Model\Config\Source\Yesno $yesNo,
        \Magento\Store\Model\System\Store $systemStore,
		\Magento\Eav\Model\Config $eavConfig,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Convert\DataObject $objectConverter,
        array $data = []
    ) {
        $this->_enableDisable = $enableDisable;
        $this->_yesNo = $yesNo;
		$this->eavConfig = $eavConfig;
        $this->systemStore = $systemStore;
        $this->_groupRepository = $groupRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_objectConverter = $objectConverter;

        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
		$rule = $this->_coreRegistry->registry('rokanthemes_setproduct');
		
		$form = $this->_formFactory->create();
		$form->setFieldNameSuffix('rule');
		
        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('General Information'),
            'class'  => 'fieldset-wide'
        ]);
		if($rule->getData()){
			$fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
		}
        $fieldset->addField('name', 'text', [
            'name'     => 'name',
            'label'    => __('Name'),
            'title'    => __('Name'),
            'required' => true
        ]);
		
		$form->setValues($rule->getData());
        $this->setForm($form);
		
        return parent::_prepareForm();
    }
	
    public function getTabLabel()
    {
        return __('Product Set');
    }
 
    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Product Set');
    }
 
    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }
 
    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
<?php
 
namespace Rokanthemes\SlideBanner\Block\Adminhtml\Slider\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
 
class Setting extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
 
    protected $_newsStatus;
    protected $_objectManager;
 
   /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
		\Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_objectManager = $objectManager;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
       /** @var $model \Rokanthemes\SlideBanner\Model\Slide */
        $model = $this->_coreRegistry->registry('slider_form_data');
		$defaultSetting = array(
            'fullwidth'=>'1',
			'effect'=>'random',
			'slices'=>'15',
			'boxCols'=>'8',
			'boxRows'=>'4',
			'animSpeed'=>'500',
			'pauseTime'=>'4000',
			'startSlide'=>'1',
			'directionNav'=>'true',
			'controlNav'=>'true',
			'controlNavThumbs'=>'false',
			'pauseOnHover'=>'false',
			'manualAdvance'=>'false',
			'prevText'=>'Prev',
			'nextText'=>'Next',
            'progressBar'=>'true',
			'randomStart'=>'false'
		);
		$setting = $model->getSliderSetting();
		
		$data = array_merge($defaultSetting, $setting);
		
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('slider_');
        $form->setFieldNameSuffix('slider');
 
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Settings')]
        );

        $fieldset->addField(
            'fullwidth',
            'select',
            [
                'name'        => 'slider_setting[fullwidth]',
                'label'    => __('Full Width'),
                'required'     => false,
                'values'=> [['value'=>'0', 'label'=> __('No')], ['value'=>'1', 'label'=> __('Yes')]]
            ]
        );

        $fieldset->addField(
            'effect',
            'select',
            [
                'name'        => 'slider_setting[effect]',
                'label'    => __('Effect'),
                'required'     => true,
                'values'=> [
                    ['value'=> 'random', 'label'=> __('Random')], 
                    ['value'=> 'sliceDownRight', 'label'=> __('Slice Down Right')],
                    ['value'=> 'sliceDownLeft', 'label'=> __('Slice Down Left')],
                    ['value'=> 'sliceUpRight', 'label'=> __('Slice Up Right')],
                    ['value'=> 'sliceUpLeft', 'label'=> __('Slice Up Left')],
                    ['value'=> 'sliceUpDown', 'label'=> __('Slice Up Down')],
                    ['value'=> 'sliceUpDownLeft', 'label'=> __('Slice Up Down Left')],
                    ['value'=> 'fold', 'label'=> __('Fold')],
                    ['value'=> 'fade', 'label'=> __('Fade')],
                    ['value'=> 'boxRandom', 'label'=> __('Box Random')],
                    ['value'=> 'boxRain', 'label'=> __('Box Rain')],
                    ['value'=> 'boxRainReverse', 'label'=> __('Box Rain Reverse')],
                    ['value'=> 'boxRainGrow', 'label'=> __('Box Rain Grow')],
                    ['value'=> 'boxRainGrowReverse', 'label'=> __('Box Rain Grow Reverse')]
                ]
            ]
        );
		
		$fieldset->addField(
            'slices',
            'text',
            [
                'name'        => 'slider_setting[slices]',
                'label'    => __('Slices'),
                'required'     => true,
				'class' => 'validate-number', 
				'default'=> 1
            ]
        );
		
		$fieldset->addField(
            'boxCols',
            'text',
            [
                'name'        => 'slider_setting[boxCols]',
                'label'    => __('Box Cols'),
                'required'     => true,
                'class' => 'validate-number', 
                'default'=> 1
            ]
        );

        $fieldset->addField(
            'boxRows',
            'text',
            [
                'name'        => 'slider_setting[boxRows]',
                'label'    => __('Box Rows'),
                'required'     => true,
                'class' => 'validate-number', 
                'default'=> 1
            ]
        );

        $fieldset->addField(
            'animSpeed',
            'text',
            [
                'name'        => 'slider_setting[animSpeed]',
                'label'    => __('Anim Speed'),
                'required'     => true,
                'class' => 'validate-number', 
                'default'=> 1
            ]
        );
		
        $fieldset->addField(
            'pauseTime',
            'text',
            [
                'name'        => 'slider_setting[pauseTime]',
                'label'    => __('Pause Time'),
                'required'     => true,
                'class' => 'validate-number', 
                'default'=> 1
            ]
        );

		$fieldset->addField(
            'startSlide',
            'select',
            [
                'name'        => 'slider_setting[startSlide]',
                'label'    => __('Start Slide'),
                'required'     => false,
				'values'=> [['value'=>'0', 'label'=> __('False')], ['value'=>'1', 'label'=> __('True')]]
            ]
        );
		
		$fieldset->addField(
            'directionNav',
            'select',
            [
                'name'        => 'slider_setting[directionNav]',
                'label'    => __('Direction Nav'),
                'required'     => false,
                'values'=> [['value'=>'false', 'label'=> __('False')], ['value'=>'true', 'label'=> __('True')]]
            ]
        );

        $fieldset->addField(
            'controlNav',
            'select',
            [
                'name'        => 'slider_setting[controlNav]',
                'label'    => __('Control Nav'),
                'required'     => false,
                'values'=> [['value'=>'false', 'label'=> __('False')], ['value'=>'true', 'label'=> __('True')]]
            ]
        );

        $fieldset->addField(
            'controlNavThumbs',
            'select',
            [
                'name'        => 'slider_setting[controlNavThumbs]',
                'label'    => __('Control Nav Thumbs'),
                'required'     => false,
                'values'=> [['value'=>'false', 'label'=> __('False')], ['value'=>'true', 'label'=> __('True')]]
            ]
        );

        $fieldset->addField(
            'pauseOnHover',
            'select',
            [
                'name'        => 'slider_setting[pauseOnHover]',
                'label'    => __('Pause On Hover'),
                'required'     => false,
                'values'=> [['value'=>'false', 'label'=> __('False')], ['value'=>'true', 'label'=> __('True')]]
            ]
        );

        $fieldset->addField(
            'manualAdvance',
            'select',
            [
                'name'        => 'slider_setting[manualAdvance]',
                'label'    => __('Manual Advance'),
                'required'     => false,
                'values'=> [['value'=>'false', 'label'=> __('False')], ['value'=>'true', 'label'=> __('True')]]
            ]
        );

        $fieldset->addField(
            'nextText',
            'text',
            [
                'name'        => 'slider_setting[nextText]',
                'label'    => __('Next Text'),
                'required'     => false
            ]
        );
        
        $fieldset->addField(
            'prevText',
            'text',
            [
                'name'        => 'slider_setting[prevText]',
                'label'    => __('Prev Text'),
                'required'     => false
            ]
        );

        $fieldset->addField(
            'randomStart',
            'select',
            [
                'name'        => 'slider_setting[randomStart]',
                'label'    => __('Random Start'),
                'required'     => false,
                'values'=> [['value'=>'false', 'label'=> __('False')], ['value'=>'true', 'label'=> __('True')]]
            ]
        );

        $fieldset->addField(
            'progressBar',
            'select',
            [
                'name'        => 'slider_setting[progressBar]',
                'label'    => __('Show Progress Bar'),
                'required'     => false,
                'values'=> [['value'=>'false', 'label'=> __('False')], ['value'=>'true', 'label'=> __('True')]]
            ]
        );
		
        $form->setValues($data);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Slider Info');
    }
 
    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Slider Info');
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
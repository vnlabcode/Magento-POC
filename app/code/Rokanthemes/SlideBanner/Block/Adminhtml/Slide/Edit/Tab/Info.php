<?php
 
namespace Rokanthemes\SlideBanner\Block\Adminhtml\Slide\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
 
class Info extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
 
    protected $_newsStatus;
    protected $_objectManager;
    protected $_systemStore;
 
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
        \Magento\Store\Model\System\Store $systemStore,
		\Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_objectManager = $objectManager;
        $this->_systemStore = $systemStore;
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
        $model = $this->_coreRegistry->registry('slide_form_data');
        $data = $model->getData();
 
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('slide_');
        $form->setFieldNameSuffix('slide');
 
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
        );
 
        $fieldset->addField(
            'slider_id',
            'select',
            [
                'name'        => 'slider_id',
                'label'    => __('Slider'),
                'required'     => true,
				'values'=>$this->_getSliderOptions()
            ]
        );
        $fieldset->addField(
           'store_ids',
           'multiselect',
           [
             'name'     => 'store_ids[]',
             'label'    => __('Store Views'),
             'title'    => __('Store Views'),
             'required' => true,
             'values'   => $this->_systemStore->getStoreValuesForForm(false, true),
           ]
        );
        $fieldset->addField(
            'slide_status',
            'select',
            [
                'name'        => 'slide_status',
                'label'    => __('Status'),
                'required'     => true,
				'values'=> [['value'=>1, 'label'=> __('Enable')], ['value'=>2, 'label'=> __('Disable')]]
            ]
        );
        $fieldset->addField(
            'slide_position',
            'text',
            [
                'name'        => 'slide_position',
                'label'    => __('Position'),
                'required'     => false
            ]
        );
        $fieldset->addField(
            'slide_image',
            'image',
            [
                'name'        => 'slide_image',
                'label'    => __('Image'),
                'required'     => true,
                'container_id' => 'show-hide-slide-image'
            ]
        );
		$fieldset->addField(
            'slide_image_mobile',
            'image',
            [
                'name'        => 'slide_image_mobile',
                'label'    => __('Image on Mobile'),
                'required'     => true,
                'container_id' => 'show-hide-slide-image-mobile'
            ]
        );
        $fieldset->addField(
            'slide_link',
            'text',
            [
                'name'        => 'slide_link',
                'label'    => __('Add Link'),
                'required'     => false,
                'container_id' => 'show-hide-slide-image-link'
            ]
        );
		$fieldset->addField(
            'opennewtab',
            'select',
            [
                'name'        => 'opennewtab',
                'label'    => __('Open Link New Tab'),
                'required'     => false,
				'values'=> [['value'=>'no', 'label'=> __('No')], ['value'=>'yes', 'label'=> __('Yes')]],
                'container_id' => 'show-hide-slide-open-new-tab'
            ]
        );
        $customField = $fieldset->addField(
            'text_animate',
            'select',
            [
                'name'        => 'text_animate',
                'label'    => __('Show Caption'),
                'required'     => false,
                'values'=> [['value'=>'no', 'label'=> __('No')], ['value'=>'yes', 'label'=> __('Yes')]],
                'container_id' => 'show-hide-slide-caption-fixed'
            ]
        );
        if(isset($data['slide_text']) && $data['slide_text'] != ''){
            $captions = $data['slide_text'];
        }
        else{
            $captions = "[]";
        }
        $customField->setAfterElementHtml('<script>
        //<![CDATA[
         
           require(["jquery"], function ($) {
                var data_caption = '.$captions.';
                $("#show-hide-slide-caption-fixed").find("div.admin__field-control").append(\'<div style="display: none;" id="add-custom-text-caption-fixed-container"><table style="width: 100%; margin: 15px 0;"><tbody><tr style=" border: 1px solid #ccc; "><th style="border-right: 1px solid #ccc; width: 50%; text-align: left; padding: 10px;">Title</th><th style="border-right: 1px solid #ccc; width: 10%; ">HTML tag</th><th style="border-right: 1px solid #ccc; width: 10%; text-align: left; padding: 15px;">Animation</th><th style="border-right: 1px solid #ccc; width: 15%; text-align: left; padding: 10px;">Color</th><th style="border-right: 1px solid #ccc; text-align: left; padding: 10px;">Custom Class</th><th style=" width: 10%; text-align: left; padding: 10px;"></th></tr></tbody></table><div><button type="button" title="Add" id="fixed-button-add-cap-click">Add</button></div></div>\');
                $("#show-hide-slide-caption-fixed").find("div.admin__field-control").css("width","80%");
                if(data_caption.length > 0){
                    for (i = 0; i < data_caption.length; i++) {
                        var data_c_each = data_caption[i];
                        var id_item_fixed = $(".caption-rows-fixed").length;
                        var html_append = \'<tr class="caption-rows-fixed" id="set-default-value-caption-fixed-\'+id_item_fixed+\'"><td style=" padding: 10px 5px 10px 0; "><input name="slide[slide_text][\'+id_item_fixed+\'][text]" value="\'+data_c_each.text+\'" type="text" class="input-text admin__control-text"></td><td style=" padding: 10px 5px 10px 0; "><div style="width: 90px;"><select name="slide[slide_text][\'+id_item_fixed+\'][display]" class="select admin__control-select group-fixed"> <option value="2">h2</option> <option value="3">h3</option> <option value="4">h4</option> <option value="5">h5</option> <option value="6">p</option> <option value="7">button</option> </select></div></td><td style=" padding: 10px 5px 10px 0; "><div style="width: 120px;"><select name="slide[slide_text][\'+id_item_fixed+\'][animated]" class=" select admin__control-select animated-fixed"> <option value="zoomIn">zoomIn</option> <option value="fadeIn">fadeIn</option> <option value="fadeInDown">fadeInDown</option> <option value="fadeInLeft">fadeInLeft</option> <option value="fadeInRight">fadeInRight</option> <option value="fadeInUp">fadeInUp</option> <option value="fadeInTopLeft">fadeInTopLeft</option> <option value="fadeInTopRight">fadeInTopRight</option> <option value="fadeInBottomLeft">fadeInBottomLeft</option> <option value="fadeInBottomRight">fadeInBottomRight</option> <option value="bounceIn">bounceIn</option> <option value="bounceInDown">bounceInDown</option> <option value="bounceInLeft">bounceInLeft</option> <option value="bounceInRight">bounceInRight</option> <option value="bounceInUp">bounceInUp</option> <option value="slideInDown">slideInDown</option> <option value="slideInLeft">slideInLeft</option> <option value="slideInRight">slideInRight</option> <option value="slideInUp">slideInUp</option> </select></div></td><td style=" padding: 10px 5px 10px 0;"><div style="width: 100px; overflow: hidden;"><input name="slide[slide_text][\'+id_item_fixed+\'][color]" value="\'+data_c_each.color+\'" type="text" class="jscolor"></div></td><td style=" padding: 10px 5px 10px 0; "><input name="slide[slide_text][\'+id_item_fixed+\'][customclass]" value="\'+data_c_each.customclass+\'" type="text" class="input-text admin__control-text"></td><td><button type="button" title="Add" class="remove-fixed-click-caption">Del</button></td></tr>\';
                        $("#add-custom-text-caption-fixed-container table tbody").append(html_append);
                        $("#set-default-value-caption-fixed-"+id_item_fixed+" .group-fixed").val(data_c_each.display);
                        $("#set-default-value-caption-fixed-"+id_item_fixed+" .animated-fixed").val(data_c_each.animated);
                    }
                    jscolor.install();
                }
                function changeShowHideCaption(){
                    var enable_cap = $("#slide_text_animate").val();
                    if(enable_cap == "yes"){
                        $("#add-custom-text-caption-fixed-container").show();
                    }
                    else{
                        $("#add-custom-text-caption-fixed-container").hide();
                    }
                }
                changeShowHideCaption();
                $("#slide_text_animate").change(function(){
                    changeShowHideCaption();
                });
                $(document).on("click",".remove-fixed-click-caption",function(){
                    $(this).closest("tr").remove();
                });
                var common_cap_line_item = 100;
                $(document).on("click","#fixed-button-add-cap-click",function(){
                    common_cap_line_item = common_cap_line_item + 10;
                    var id_item_fixed = common_cap_line_item;
                    var html_append = \'<tr class="caption-rows-fixed"><td style=" padding: 10px 5px 10px 0; "><input name="slide[slide_text][\'+id_item_fixed+\'][text]" value="" type="text" class="input-text admin__control-text"></td><td style=" padding: 10px 5px 10px 0; "><div style="width: 90px;"><select name="slide[slide_text][\'+id_item_fixed+\'][display]" class="select admin__control-select"> <option value="2">h2</option> <option value="3">h3</option> <option value="4">h4</option> <option value="5">h5</option> <option value="6">p</option> <option value="7">button</option> </select></div></td><td style=" padding: 10px 5px 10px 0; "><div style="width: 120px;"><select name="slide[slide_text][\'+id_item_fixed+\'][animated]" class=" select admin__control-select"> <option value="zoomIn">zoomIn</option> <option value="fadeIn">fadeIn</option> <option value="fadeInDown">fadeInDown</option> <option value="fadeInLeft">fadeInLeft</option> <option value="fadeInRight">fadeInRight</option> <option value="fadeInUp">fadeInUp</option> <option value="fadeInTopLeft">fadeInTopLeft</option> <option value="fadeInTopRight">fadeInTopRight</option> <option value="fadeInBottomLeft">fadeInBottomLeft</option> <option value="fadeInBottomRight">fadeInBottomRight</option> <option value="bounceIn">bounceIn</option> <option value="bounceInDown">bounceInDown</option> <option value="bounceInLeft">bounceInLeft</option> <option value="bounceInRight">bounceInRight</option> <option value="bounceInUp">bounceInUp</option> <option value="slideInDown">slideInDown</option> <option value="slideInLeft">slideInLeft</option> <option value="slideInRight">slideInRight</option> <option value="slideInUp">slideInUp</option> </select></div></td><td style=" padding: 10px 5px 10px 0;"><div style="width: 100px; overflow: hidden;"><input name="slide[slide_text][\'+id_item_fixed+\'][color]" value="" type="text" class="jscolor"></div></td><td style=" padding: 10px 5px 10px 0; "><input name="slide[slide_text][\'+id_item_fixed+\'][customclass]" type="text" class="input-text admin__control-text"></td><td><button type="button" title="Add" class="remove-fixed-click-caption">Del</button></td></tr>\';
                    $("#add-custom-text-caption-fixed-container table tbody").append(html_append);
                    jscolor.install();
                });
           });
         
        //]]>
        </script>');
        $fieldset->addField(
            'text_position',
            'select',
            [
                'name'        => 'text_position',
                'label'    => __('Caption Position'),
                'required'     => false,
                'values'=> [['value'=>'left_top', 'label'=> __('Left Top')], ['value'=>'left_center', 'label'=> __('Left Center')], ['value'=>'left_bottom', 'label'=> __('Left Bottom')], ['value'=>'center_top', 'label'=> __('Center Top')], ['value'=>'center_center', 'label'=> __('Center Center')], ['value'=>'center_bottom', 'label'=> __('Center Bottom')], ['value'=>'right_top', 'label'=> __('Right Top')], ['value'=>'right_center', 'label'=> __('Right Center')], ['value'=>'right_bottom', 'label'=> __('Right Bottom')]],
                'container_id' => 'show-hide-slide-text-postion'
            ]
        );
 
        if(isset($data['store_ids']) && $data['store_ids'] != ''){
            $data['store_ids'] = json_decode($data['store_ids'], true);
        }
        $form->setValues($data);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
	protected function _getSliderOptions()
	{
		$result = [];
		$collection = $this->_objectManager->create('Rokanthemes\SlideBanner\Model\Slider', [])->getCollection();
		foreach($collection as $slider)
		{
			$result[] = array('value'=>$slider->getId(), 'label'=>$slider->getSliderTitle());
		}
		return $result;
	}
    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Banner Info');
    }
 
    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Banner Info');
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
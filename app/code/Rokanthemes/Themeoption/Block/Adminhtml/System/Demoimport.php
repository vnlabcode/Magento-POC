<?php

namespace Rokanthemes\Themeoption\Block\Adminhtml\System;

use Magento\Framework\App\Filesystem\DirectoryList;

class Demoimport extends \Magento\Config\Block\System\Config\Form\Fieldset
{
	protected $_getFile;
	protected $_geDir;
	protected $_themeFactory;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $auth,
        \Magento\Framework\View\Helper\Js $js,
		\Magento\Framework\Filesystem $file,
		\Magento\Theme\Model\ThemeFactory $themeFactory,
        array $data = []
    ) {
        parent::__construct($context, $auth, $js, $data);
		$this->_getFile = $file;
		$this->_geDir = $this->_getFile->getDirectoryRead(DirectoryList::APP)->getAbsolutePath('code/Rokanthemes/Themeoption');
		$this->_themeFactory = $themeFactory;
    }
	
	protected function _getHeaderCommentHtml($element)
    {
    	$load_parser_xml = new \Magento\Framework\Xml\Parser();
    	$data_theme = $load_parser_xml->load($this->_geDir.'/demo/data.xml')->xmlToArray();

    	$model_themes = $this->_themeFactory->create();
		$theme_collection = $model_themes->getCollection();
		$themes = [];
		if($theme_collection->count()){
			foreach ($theme_collection as $val_theme) {
				$codes = explode("/", $val_theme->getCode());
				$themes[$val_theme->getThemeId()] = end($codes);
			}
		}

		$store_id = ($this->getRequest()->getParam('store')) ? $this->getRequest()->getParam('store') : 'default';
		$website_id = ($this->getRequest()->getParam('website')) ? $this->getRequest()->getParam('website') : 'default';

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableCoreConfigData = $resource->getTableName('core_config_data');
        $sql_default_theme = "SELECT * FROM ".$tableCoreConfigData." WHERE path LIKE '%design/theme/theme_id%' AND scope = 'default'";
        $result_default_theme = $connection->fetchRow($sql_default_theme);
        
        $selected_theme_id = false;
        if(isset($result_default_theme['value'])){
        	$selected_theme_id = $result_default_theme['value'];
        }

        if($this->getRequest()->getParam('website')){
        	$sql_website_theme = "SELECT * FROM ".$tableCoreConfigData." WHERE path LIKE '%design/theme/theme_id%' AND scope = 'websites' AND scope_id = ".$this->getRequest()->getParam('website');
        	$result_website_theme = $connection->fetchRow($sql_website_theme);
        	if(isset($result_website_theme['value'])){
	        	$selected_theme_id = $result_website_theme['value'];
	        }
        }

        if($this->getRequest()->getParam('store')){
        	$sql_store_theme = "SELECT * FROM ".$tableCoreConfigData." WHERE path LIKE '%design/theme/theme_id%' AND scope = 'stores' AND scope_id = ".$this->getRequest()->getParam('store');
        	$result_store_theme = $connection->fetchRow($sql_store_theme);
        	if(isset($result_store_theme['value'])){
	        	$selected_theme_id = $result_store_theme['value'];
	        }
        }

    	if(isset($data_theme['root']['rokanthemes']['item']['id'])){
    		$html = '<table class="form-list" id="table-container-rokanthemes-importer" cellspacing="0"><tbody>';
	        	$html .= '<tr><td style="padding: 0;">';
	        		$html .= '<h2 class="title-theme-name-fixed">'.$data_theme['root']['rokanthemes']['item']['name'].'</h2>';
	        		$html .= '<p class="des-theme-name-fixed">'.$data_theme['root']['rokanthemes']['item']['des'].'</p>';
	        		if(isset($data_theme['root']['rokanthemes']['item']['theme']) && is_array($data_theme['root']['rokanthemes']['item']['theme']) && !empty($data_theme['root']['rokanthemes']['item']['theme'])){
	        			$install_theme = false;
	        			$html .= '<div class="rokanthemes-container-import-theme-items">';
	        				foreach ($data_theme['root']['rokanthemes']['item']['theme'] as $val_item) {
	        					if(in_array($val_item['identifier'], $themes)){
	        						$install_theme = true;
	        						$theme_id_fixed = array_search($val_item['identifier'], $themes);
		        					$html .= '<div class="item-theme-import-fixed-container">
		        								<div class="info-theme-screenshot">
		        									<img src="'.$this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]).'demo_importer/preview/'.$val_item['image'].'" alt="'.$val_item['title'].'">
		        								</div>';
		        								if(!$selected_theme_id || $selected_theme_id != $theme_id_fixed){
		        									$html .= '<div class="info-theme-name-and-activate">
			        									<h2 class="item-theme-name" id="'.$val_item['identifier'].'">'.$val_item['title'].'</h2>
			        									<div class="item-theme-actions">
			        										<a class="item-button-live-preview" target="_blank" href="//'.$val_item['linkpreview'].'">'.__("Live Preview").'</a>
			        										<a class="item-button-activate" href="#" data-theme-title="'.$val_item['title'].'" data-theme-id="'.$theme_id_fixed.'" data-theme-name="'.$val_item['identifier'].'">'.__("Activate").'</a>
			        									</div>
			        								</div>';
		        								}
		        								else{
			        								$html .= '<div class="info-theme-name-and-activate theme-has-selected-fix-style">
			        									<h2 class="item-theme-name">'.__('Active: ').$val_item['title'].'</h2>
			        								</div>';
		        								}
		        					$html .= '</div>';
		        				}
	        				}
	        			$html .= '</div>';
	        			if(!$install_theme){
	        				$html .= '<div class="rokanthemes-container-empty-theme">
	        					<p>'.__('You have not install theme. Please install the our theme:').'</p>
	        					<p>1. '.__('Download our theme package files. Extract this package, upload folders in base package: app, lib, pub to the root directory ( www, public_html) of your magento folder. You can use a FTP software, such as FileZilla, then logging into your hosting to do it. If you use magento 2.4.x version, after uploaded folder in bs_base then overwrite file in bs_base_v.2.4.x.').'</p>
	        					<p>2. '.__('Make sure that you upload all theme folders and files successfully. You disable Maintenance Mode for your store.').'</p>
	        					<p>3. '.__('Go to SSH on server and cd to root magento and run commandlines below:').'</p>
	        					<p>php bin/magento indexer:reindex</p>
	        					<p>php bin/magento setup:upgrade</p>
	        					<p>php bin/magento setup:static-content:deploy -f</p>
	        					<p>php bin/magento cache:flush</p>
	        					<p>chmod 777 -R generated var pub (This is command run in Linux)</p>
	        				</div>';
	        			}
	        		}
	        	$html .= '</td></tr>';
			$html .= '</tbody></table>';
			$url_ajax = $this->getUrl("themeoption/demoimporter/runimport");
			$html .= '<script>    
					    require([
					        "jquery"
					    ], function($) {
					    	$(".item-button-activate").click(function(){
					    		var theme_name = $(this).attr("data-theme-title");
					    		var params = {id: "'.$data_theme['root']['rokanthemes']['item']['id'].'",themetitle:  $(this).attr("data-theme-title"), themename: $(this).attr("data-theme-name"), themeid: $(this).attr("data-theme-id"), store: "'.$store_id.'", website: "'.$website_id.'"};
							  	$.ajax({
				                    url: "'.$url_ajax.'",
				                    data: params,
				                    type: "post",
				                    showLoader: true,
				                    dataType: "json",
				                    success: function (res) {
				                    	if(res.result == "success"){
				                    		alert(theme_name+" has been activated successfully!");
				                        	location.reload();
				                    	}
				                    	else{
				                    		alert("Your license is invalidated. Please go to: Rokanthemes > Rokanthemes Theme > Activation Purchase Code");
				                    	}
				                    },
				                    error: function (res) {
				                        alert("Error in sending ajax request");
				                    }
			                	});
							  	return false;
							});
					    });
					</script>';
    	}
    	else{
    		$html = '<table class="form-list" cellspacing="0"><tbody>';
	        	$html .= '<tr><td style="padding: 0;">';
	        		$html .= '<h2 class="title-theme-name-fixed">'.__("Can not get the data file.").'</h2>';
	        	$html .= '</td></tr>';
			$html .= '</tbody></table>';
    	}
		
        return $html;
    }
}

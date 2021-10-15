<?php

namespace Rokanthemes\Themeoption\Controller\Adminhtml\Demoimporter;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\PageCache\Version;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class Runimport extends \Magento\Backend\App\Action
{
    protected $fileFactory;

    protected $_parser;

    protected $resultJsonFactory;

    protected $_config;

    protected $cacheTypeList;

	protected $cacheFrontendPool;

	protected $indexFactory;
   	
   	protected $indexCollection;

   	protected $_getFile;

	protected $_geDir;

	protected $_css;

    protected $_themeoptionversion;

    protected $_verifypurchasecode;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $config,
        \Magento\Indexer\Model\IndexerFactory $indexFactory,
 		\Magento\Indexer\Model\Indexer\CollectionFactory $indexCollection,
 		\Magento\Framework\Filesystem $file,
 		\Rokanthemes\Themeoption\Model\Custom\Generator $css,
 		\Rokanthemes\Themeoption\Model\ThemeoptionversionFactory $themeoptionversion,
        \Rokanthemes\Themeoption\Helper\Verifypurchasecode $verifypurchasecode,
        TypeListInterface $cacheTypeList, 
    	Pool $cacheFrontendPool
    ) {
        parent::__construct($context);
        $this->_verifypurchasecode = $verifypurchasecode;
        $this->fileFactory = $fileFactory;
		$this->_importPath = BP. '/' . DirectoryList::PUB . '/' . DirectoryList::MEDIA . '/demo_importer/';
		$this->_parser = new \Magento\Framework\Xml\Parser();
		$this->_config = $config;
		$this->resultJsonFactory = $resultJsonFactory;
		$this->cacheTypeList = $cacheTypeList;
    	$this->cacheFrontendPool = $cacheFrontendPool;
    	$this->indexFactory = $indexFactory;
        $this->indexCollection = $indexCollection;
        $this->_getFile = $file;
		$this->_geDir = $this->_getFile->getDirectoryRead(DirectoryList::APP)->getAbsolutePath('code/Rokanthemes/Themeoption');
		$this->_css = $css;
        $this->_themeoptionversion = $themeoptionversion;
    }

    public function execute()
    {
    	$resultJson = $this->resultJsonFactory->create();

        if($this->_verifypurchasecode->checkEnvatoPurchaseCode()){
            $id = $this->getRequest()->getPost('id');
            $themename = $this->getRequest()->getPost('themename');
            $themetitle = $this->getRequest()->getPost('themetitle');
            $themeid = $this->getRequest()->getPost('themeid');
            $store = $this->getRequest()->getPost('store');
            $website = $this->getRequest()->getPost('website');
            
            $xmlPathPage = $this->_importPath . 'cms_pages.xml';
            $error = '';
            if (!is_readable($xmlPathPage))
            {
                $error = __("Can't get the data file for import cms pages.");
            }
            else{
                $dataPage = $this->_parser->load($xmlPathPage)->xmlToArray();
                if(isset($dataPage['root']['pages']['item'])){
                    foreach($dataPage['root']['pages']['item'] as $_item) {
                        $collection = $this->_objectManager->create('Magento\Cms\Model\Page')->getCollection();
                        $collection->addFieldToFilter('identifier', $_item['identifier']);
                        if(!$collection->getSize()){
                            $page = $this->_objectManager->create('Magento\Cms\Model\Page');
                            $_item['store_id'] = array(0);
                            $page->addData($_item)->save();
                        }
                    }
                }
            }

            $xmlPathBlocks = $this->_importPath . 'cms_blocks.xml';
            if (!is_readable($xmlPathBlocks))
            {
                $error .= __("Can't get the data file for import cms pages.");
            }
            else{
                $dataBlocks = $this->_parser->load($xmlPathBlocks)->xmlToArray();
                if(isset($dataBlocks['root']['blocks']['item'])){
                    foreach($dataBlocks['root']['blocks']['item'] as $_itemblock) {
                        $allow_blocks_imported = true;
                        if($allow_blocks_imported){
                            $collection = $this->_objectManager->create('Magento\Cms\Model\Block')->getCollection();
                            $collection->addFieldToFilter('identifier', $_itemblock['identifier']);
                            if(!$collection->getSize()){
                                $block = $this->_objectManager->create('Magento\Cms\Model\Block');
                                $_itemblock['store_id'] = array(0);
                                $block->addData($_itemblock)->save();
                            }
                        }
                    }
                }
            }

            $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $scope = 'default';
            $scope_id = 0;
            $website_id = false;
            $store_id = false;
            if($website != 'default'){
                $scope = 'websites';
                $scope_id = $website;
                $website_id = $website;
            }
            if($store != 'default'){
                $scope = 'stores';
                $scope_id = $store;
                $store_id = $store;
            }
            $this->_config->saveConfig('web/default/cms_home_page',$themename,$scope,$scope_id);
            $this->_config->saveConfig('design/theme/theme_id',$themeid,$scope,$scope_id);

            if (is_readable($this->_geDir.'/demo/fonts.xml'))
            {
                $data_fonts = $this->_parser->load($this->_geDir.'/demo/fonts.xml')->xmlToArray();

                if(isset($data_fonts['root']['default']['themeoption']['header'])){
                    $header_default = $data_fonts['root']['default']['themeoption']['header'];
                    if(isset($header_default[$themename])){
                        $this->_config->saveConfig('themeoption/header/select_header_type',$header_default[$themename],$scope,$scope_id);
                    }
                }
                if(isset($data_fonts['root']['default']['themeoption']['footer'])){
                    $footer_default = $data_fonts['root']['default']['themeoption']['footer'];
                    if(isset($footer_default[$themename])){
                        $this->_config->saveConfig('themeoption/header/select_footer_type',$footer_default[$themename],$scope,$scope_id);
                    }
                }

                $default_fonts = isset($data_fonts['root']['default']['themeoption']['font']) ? $data_fonts['root']['default']['themeoption']['font'] : [];
                $custom_fonts = isset($data_fonts['root'][$themename]['themeoption']['font']) ? $data_fonts['root'][$themename]['themeoption']['font'] : [];
                if(count($custom_fonts) > 0){
                    $final_fonts = array_replace($default_fonts, $custom_fonts);
                }
                else{
                    $final_fonts = $default_fonts;
                }
                if(count($final_fonts) > 0){
                    foreach($final_fonts as $key_font => $val_font){
                        $this->_config->saveConfig('themeoption/font/'.$key_font,$val_font,$scope,$scope_id);
                    }
                }
            }

            $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $tableRokanthemesSlider = $resource->getTableName('rokanthemes_slider');
            $tableRokanthemesSlide = $resource->getTableName('rokanthemes_slide');

            $xmlPathSlideshow = $this->_importPath . 'slidebanner.xml';
            if (is_readable($xmlPathSlideshow))
            {
                $dataSlideshow = $this->_parser->load($xmlPathSlideshow)->xmlToArray();
                if(isset($dataSlideshow['root']['slider']['item']) && is_array($dataSlideshow['root']['slider']['item']) && !empty($dataSlideshow['root']['slider']['item'])){
                    foreach ($dataSlideshow['root']['slider']['item'] as $key_slider => $val_slider) {
                        $collection = $this->_objectManager->create('Rokanthemes\SlideBanner\Model\Slider')->getCollection();
                        $collection->addFieldToFilter('slider_identifier', $val_slider['slider_identifier']);
                        if(!$collection->getSize()){
                            $sql_insert_slider = "INSERT INTO `".$tableRokanthemesSlider."` (`slider_id`, `slider_identifier`, `slider_title`, `slider_status`, `slider_setting`, `created_at`) VALUES ('".$val_slider['slider_id']."', '".$val_slider['slider_identifier']."', '".$val_slider['slider_title']."', '".$val_slider['slider_status']."', '".$val_slider['slider_setting']."', '".$val_slider['created_at']."');";
                            $connection->query($sql_insert_slider);
                        }
                    }
                }

                if(isset($dataSlideshow['root']['slide']['item']) && is_array($dataSlideshow['root']['slide']['item']) && !empty($dataSlideshow['root']['slide']['item'])){
                    foreach ($dataSlideshow['root']['slide']['item'] as $key_slide => $val_slide) {
                        $collection = $this->_objectManager->create('Rokanthemes\SlideBanner\Model\Slide')->getCollection();
                        $collection->addFieldToFilter('slide_id', $val_slide['slide_id']);
                        if(!$collection->getSize()){
                            $sql_insert_slide = "INSERT INTO `".$tableRokanthemesSlide."` (`slide_id`, `slider_id`, `store_ids`, `slide_status`, `slide_position`, `slide_type`, `slide_video`, `slide_image`, `slide_image_mobile`, `slide_link`, `opennewtab`, `slide_text`, `text_position`, `text_animate`, `created_at`) VALUES ('".$val_slide['slide_id']."', '".$val_slide['slider_id']."', '".json_encode([0])."', '".$val_slide['slide_status']."', '".$val_slide['slide_position']."', '".$val_slide['slide_type']."', '".$val_slide['slide_video']."', '".$val_slide['slide_image']."', '".$val_slide['slide_image_mobile']."', '".$val_slide['slide_link']."', '".$val_slide['opennewtab']."', '', '".$val_slide['text_position']."', '', '".$val_slide['created_at']."');";
                            $connection->query($sql_insert_slide);
                        }
                    }
                }
            }

            $_types = [
                'config',
                'layout',
                'block_html',
                'full_page'
            ];

            foreach ($_types as $type) {
                $this->cacheTypeList->cleanType($type);
            }
            foreach ($this->cacheFrontendPool as $cacheFrontend) {
                $cacheFrontend->getBackend()->clean();
            }

            $indexidarray = $this->indexFactory->create()->load('design_config_grid');
            $indexidarray->reindexAll('design_config_grid');

            $t_option = $this->_themeoptionversion->create();
            $t_option->setVersion(strtotime('now'));
            $t_option->setVersionTime(date('Y-m-d H:i:s'));
            $t_option->save();
            $this->_css->generateCss($website_id, $store_id);
            
            $response = ['result' => 'success'];
        }
        else{
            $response = ['result' => 'error'];
        }

        return $resultJson->setData($response);
    }
}
?>
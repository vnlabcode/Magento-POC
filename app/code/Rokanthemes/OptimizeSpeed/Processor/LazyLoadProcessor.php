<?php
namespace Rokanthemes\OptimizeSpeed\Processor;

use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\App\View\Deployment\Version\StorageInterface;
use Rokanthemes\OptimizeSpeed\Api\Processor\OutputProcessorInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class LazyLoadProcessor implements OutputProcessorInterface
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $deploymentVersion;
	
	/**
     * @var string
     */
    private $_urlInterface;
	
	/**
     * @var string
     */
	private $_assetRepo;
	
	/**
     * @var string
     */
	private $_storeManager;
	

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Request $request,
		\Magento\Framework\UrlInterface $urlInterface,    
		\Magento\Framework\View\Asset\Repository $assetRepo,
		\Magento\Store\Model\StoreManagerInterface $storeManager,        
        StorageInterface $storage
    )
    {
		$this->scopeConfig = $scopeConfig;
        $this->request           = $request;
        $this->deploymentVersion = $storage->load();
		$this->_assetRepo = $assetRepo;
		$this->_storeManager = $storeManager;
		$this->_urlInterface = $urlInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function process($content)
    {
		if (!$this->scopeConfig->getValue( 'optimizespeed/lazyload/enable', ScopeInterface::SCOPE_STORE )) {
            return $content;
        }
		$loadding_image = $this->_assetRepo->getUrl("Rokanthemes_OptimizeSpeed::images/load_defaut.png");
		
		$ignore_list = $this->scopeConfig->getValue('optimizespeed/lazyload/ignore', ScopeInterface::SCOPE_STORE );
		$ignore_list = explode(",",$ignore_list);
		$url_image  = [];
        $config_url_image = $this->scopeConfig->getValue( 'optimizespeed/lazyload/url_image', ScopeInterface::SCOPE_STORE );
        $config_url_image = json_decode($config_url_image, true);
        if (is_array($config_url_image)) {
            foreach ($config_url_image as $item) {
                $item    = (array)$item;
                $url_image[] = $item['expression'];
            }
        }
        $url_image_ignore = $url_image;
        $output = preg_replace('/<script[^>]*>(?>.*?<\/script>)/is', '', $content);
        if (preg_match_all('/<img([^>]*?)src=(\"|\'|)(.*?)(\"|\'| )(.*?)>/is', $output, $images)) {
			foreach ($images[0] as $key => $image) {
				$ignore = false;
				$attribute = $images[1][$key].$images[5][$key];
				$replace = 'src=' . $images[2][$key] . $images[3][$key] . $images[4][$key];
				if($this->scopeConfig->getValue('optimizespeed/lazyload/ignore',ScopeInterface::SCOPE_STORE)){
					foreach($ignore_list as $value){
						if(strpos($attribute, $value) !== false){
							$ignore = true;
						}
					}
				}
				if(count($url_image_ignore) > 0){
					foreach ($url_image_ignore as $val_url) {
						if(strpos($replace, $val_url) !== false){
							$ignore = true;
						}
					}
				}
				if($ignore){
					continue;
				}
				
				$newImg = str_replace($replace, 'src="'.$loadding_image.'" data-' . $replace, $image);

				$newImg = preg_replace('/srcset=[\"\'\s]+(.*?)[\"\']+/is', '', $newImg);
                $content = str_replace($image, $newImg, $content);
			}
			$content = str_replace('absolute-content-image', 'absolute-content-image lazyload-content', $content);
			$content = str_replace('page-wrapper', 'page-wrapper lazyload-image', $content);
			$script = '<script>
                        require(["jquery"], function ($) {
                            require(["rokanthemes/lazyloadimg"], function(lazy) {
								$("img[data-src]").lazy({
									"bind":"event",
									"attribute": "data-src",
									visibleOnly: true,
									threshold: 0,
									enableThrottle: true,
									throttle: 500,
									afterLoad: function(element) {
										$(element).addClass("lazy-loaded");
										$(element).closest(".absolute-content-image").removeClass("lazyload-content");
										setTimeout(function(){
											$(element).addClass("transition");
										}, 1000);
									}
								});  
								$(document).ready(function($) { 
									var win = $(window);
									$(".owl-carousel").on("translated.owl.carousel", function(event) { 
										var bounds = $(this).offset();
										var viewport = {
											top : win.scrollTop(),
											left : win.scrollLeft()
										};
										viewport.bottom = viewport.top + win.height();
										if(viewport.bottom > bounds.top){
											$(this).find("img[data-src]").lazy({
												"bind":"event",
												delay: 0,
												afterLoad: function(element) {
													$(element).addClass("lazy-loaded");
													$(element).closest(".absolute-content-image").removeClass("lazyload-content");
													setTimeout(function(){
														$(element).addClass("transition");
													}, 1000);
												}
											}); 
										}
									});
									setTimeout(function(){ 
										$("img[data-src]").each(function() {
											if($(this).is(":hidden") ) {
												var new_url = $(this).attr("data-src");
												$(this).attr("src", new_url);
												$(this).removeAttr("data-src");
												$(this).addClass("lazy-loaded");
												$(this).closest(".absolute-content-image").removeClass("lazyload-content");
												setTimeout(function(){ 
													$(this).addClass("transition");
												}, 1000);
											}
										});
									}, 1000);
								});
                            });
                        });
                    </script>';
			$content = str_replace('</body', $script . '</body', $content); 
			return $content;
		}
		return $content;
    }
}

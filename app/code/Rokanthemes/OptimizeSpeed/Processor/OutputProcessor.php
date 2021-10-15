<?php
namespace Rokanthemes\OptimizeSpeed\Processor;

use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\App\View\Deployment\Version\StorageInterface;
use Rokanthemes\OptimizeSpeed\Api\Processor\OutputProcessorInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class OutputProcessor implements OutputProcessorInterface
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
	protected $_assetRepo;
	

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Request $request,
		
		\Magento\Framework\UrlInterface $urlInterface,    
		\Magento\Framework\View\Asset\Repository $assetRepo,
        StorageInterface $storage
    )
    {
		$this->scopeConfig = $scopeConfig;
        $this->request           = $request;
        $this->deploymentVersion = $storage->load();
		$this->_assetRepo = $assetRepo;
		$this->_urlInterface = $urlInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function process($content)
    {
        if ($this->request->isAjax() || strpos($content, '{"') === 0) {
            return $content;
        }
		$fonts  = [];
        $config = $this->scopeConfig->getValue(
            'optimizespeed/optimizespeed_html/preload',
            ScopeInterface::SCOPE_STORE
        );
        $config = json_decode($config, true);
        if (is_array($config)) {
            foreach ($config as $item) {
                $item    = (array)$item;
				if(isset($item['expression'])){
					$fonts[] = $item['expression'];
				}
            }
        }
        $preloadFonts = $fonts;
        $preload = '';
        foreach ($preloadFonts as $font) {
			if(strpos($font, '://') !== false){
				$font = $font;
			}else{
				$font = $this->_assetRepo->getUrl($font);
			}
            $preload .= '<link rel="preload" href="' . $font . '" as="font" crossorigin="anonymous"/>';
        }
        $content = preg_replace('/<\/\s*title\s*>/is', '</title>' . $preload, $content);
        return $content;
    }
}

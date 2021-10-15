<?php
namespace Rokanthemes\OptimizeSpeed\Observer;

use Magento\Framework\Event\ObserverInterface;

class MinifyHtml implements ObserverInterface
{
	
	/**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;
    
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig  = $scopeConfig;        
    }
	
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		$minify_html = $this->scopeConfig->getValue(
            'optimizespeed/optimizespeed_html/enabled_minifi',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($minify_html) {
            $response = $observer->getEvent()->getResponse();
            $content     = $response->getBody();
            if (stripos($content, '<!DOCTYPE html') !== false) {
                $type = true;
                $headers = $response->getHeaders()->toArray();
                if (array_key_exists('Content-Type', $headers) && $headers['Content-Type'] == 'application/json' ) {
                    $type = false;
                }
                if ($type) {
                    $response->setBody(
                        $this->minifyHtml($content)
                    );
                }
            }
        }
    }
	
	public function minifyHtml($content) {
        $this->content = str_replace("\r\n", "\n", trim($content));
		
		$this->content = preg_replace('/^\\s+|\\s+$/m', '', $this->content);

        $this->content = preg_replace(
            '/\\s+(<\\/?(?:area|article|aside|base(?:font)?|blockquote|body'
            .'|canvas|caption|center|col(?:group)?|dd|dir|div|dl|dt|fieldset'
            .'|figcaption|figure|footer|form|frame(?:set)?|h[1-6]|head|header'
            .'|hgroup|hr|html|legend|li|link|main|map|menu|meta|nav|ol'
            .'|opt(?:group|ion)|output|p|param|section'
            .'|t(?:able|body|head|d|h||r|foot|itle)|ul|video)\\b[^>]*>)/i',
            '$1',
            $this->content
        );

        $this->content = preg_replace(
            '/>(\\s(?:\\s*))?([^<]+)(\\s(?:\s*))?</',
            '>$1$2$3<',
            $this->content
        );

        $this->content = preg_replace('/\s+/ui', ' ', $this->content);
		
		return $this->content;
    }
}

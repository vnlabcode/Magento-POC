<?php
namespace Rokanthemes\ProductTab\Controller\Tab;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\Action;

/**
 * Custom page for storefront. Needs to be accessible by POST because of the store switching.
 */
class View extends Action implements HttpGetActionInterface, HttpPostActionInterface
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $page = $this->_pageFactory->create();
        if($this->getRequest()->isXmlHttpRequest())
        {
            $layout = $page->getLayout();
            $response = $layout->getBlock('product.list')
                ->setTemplate('Rokanthemes_ProductTab::product/list.phtml')
                ->toHtml();
            $this->getResponse()->setBody($response);
            return '';
        }
        return $page;
    }
}

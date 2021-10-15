<?php
namespace Rokanthemes\ProductTab\Controller\Adminhtml\Populate;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Position
 * @package Rokanthemes\OnePageCheckout\Controller\Adminhtml\Field
 */
class MostViewed extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    protected $_cronJobObject;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Rokanthemes\ProductTab\Cron\Populate\MostViewed $object
    ) {
        $this->_cronJobObject = $object;
        parent::__construct($context);
    }

    /**
     * @return Page
     */
    public function execute()
    {
        $this->_cronJobObject->execute();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
//        $resultRedirect = $this->resultRedirectFactory->create();
//        $this->messageManager->addSuccessMessage(__('Populate Success.'));
        die(__('Finished'));
    }
}

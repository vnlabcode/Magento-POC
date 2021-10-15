<?php

namespace Rokanthemes\SetProduct\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Rokanthemes\SetProduct\Model\ProductSetFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Result\PageFactory;

abstract class ProductAction extends Action
{
    public $ruleFactory;
    public $coreRegistry;
	public $resultPageFactory;
    protected $_logger;
    public function __construct(
        ProductSetFactory $ruleFactory,
		PageFactory $resultPageFactory,
		DateTime $date,
        Registry $coreRegistry,
        Context $context
    ) {
        $this->ruleFactory  = $ruleFactory;
        $this->coreRegistry = $coreRegistry;
		$this->date = $date;
		$this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    protected function initRule($register = false)
    {
        $ruleId = (int)$this->getRequest()->getParam('id');
		
        $rule = $this->ruleFactory->create();
        if ($ruleId) {
            $rule->load($ruleId);
            if (!$rule->getId()) {
                $this->messageManager->addErrorMessage(__('This Product Set no longer exists.'));
                return false;
            }
        }
        if ($register) {
            $this->coreRegistry->register('rokanthemes_setproduct', $rule); 
        }
        return $rule;
    }
}

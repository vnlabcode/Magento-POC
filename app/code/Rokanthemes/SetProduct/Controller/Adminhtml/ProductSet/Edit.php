<?php
namespace Rokanthemes\SetProduct\Controller\Adminhtml\ProductSet;

use Rokanthemes\SetProduct\Controller\Adminhtml\ProductAction;

class Edit extends ProductAction
{ 
	public function execute()
    {
        $labels = $this->initRule();
        if (!$labels) {
			
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*');
            return $resultRedirect;
        }

        $data = $this->_session->getData('rokanthemes_setproduct_data', true); 
        if (!empty($data)) {
			
            $labels->setData($data);
        }
		
        $this->coreRegistry->register('rokanthemes_setproduct', $labels);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Manage Product Set'));
        $title = $labels->getId() ? $labels->getName() : __('Create Product Set');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }
}
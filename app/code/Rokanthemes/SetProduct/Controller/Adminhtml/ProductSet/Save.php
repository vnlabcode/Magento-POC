<?php
namespace Rokanthemes\SetProduct\Controller\Adminhtml\ProductSet;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Helper\Js;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Rokanthemes\SetProduct\Controller\Adminhtml\ProductAction;
use RuntimeException;

class Save extends ProductAction
{

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getPost('rule')) {
			$rule = $this->initRule();
            $rule->loadPost($data);
			$data = $this->getRequest()->getPostValue();
            $this->prepareData($rule, $data);
            $this->_eventManager->dispatch('rokanthemes_setproduct_prepare_save', [ 
                'post'    => $rule,
                'request' => $this->getRequest()
            ]);
            try {
                $rule->save();
				$data = $this->getRequest()->getPost('rule');
				if(isset($data['entity_id']) && $data['entity_id']){
					$this->messageManager->addSuccessMessage(__('Edit success.'));
				}else{
					$this->messageManager->addSuccessMessage(__('Add success.'));
				}
                $this->_getSession()->setData('levbon_updateinventory_data', false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath('addproductsset/*/edit', ['id' => $rule->getId(), '_current' => true]);
                } else {
                    $resultRedirect->setPath('addproductsset/*/');
                }
                return $resultRedirect;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the.'));
            }
            $resultRedirect->setPath('addproductsset/*/edit', ['id' => $rule->getId(), '_current' => true]);
            return $resultRedirect;
        }
        $resultRedirect->setPath('addproductsset/*/');

        return $resultRedirect;
    }
	
    protected function prepareData($rule, $data = [])
    {
        if ($rule->getCreatedAt() === null) {
            $data['created_at'] = $this->date->date();
        }

        $data['updated_at'] = $this->date->date();
        $rule->addData($data);

        return $this;
    }
}

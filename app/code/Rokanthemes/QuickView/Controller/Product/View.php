<?php

namespace Rokanthemes\QuickView\Controller\Product;

class View extends \Magento\Catalog\Controller\Product\View
{
     /**
      * Product view action
      *
      * @return \Magento\Framework\Controller\Result\Forward|\Magento\Framework\Controller\Result\Redirect
      */
    public function execute()
    {
        if ($this->getRequest()->getParam('quickview')) {
            // Get initial data from request
            $categoryId = (int) $this->getRequest()->getParam('category', false);
            $productId = (int) $this->getRequest()->getParam('id');
            $specifyOptions = $this->getRequest()->getParam('options');
            // Prepare helper and params
            $params = new \Magento\Framework\DataObject();
            $params->setCategoryId($categoryId);
            $params->setSpecifyOptions($specifyOptions);

            // Render page
            try {
                $page = $this->resultPageFactory->create();
                $page->getLayout()->getUpdate()->addHandle($this->getRequest()->getFullActionName());
                $this->getRequest()->setRouteName('catalog');
                $this->viewHelper->prepareAndRender($page, $productId, $this, $params);
                $newPage = $this->resultPageFactory->create();
                foreach ($page->getLayout()->getUpdate()->getHandles() as $handle) {
                    $newPage->getLayout()->getUpdate()->addHandle($handle);
                }
                $newPage->getLayout()->getUpdate()->load();
                return $this->getResponse()->representJson(json_encode(['content' => $newPage->getLayout()->renderNonCachedElement('content')]));
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                return parent::execute();
            } catch (\Exception $e) {
                return parent::execute();
            }
        }
        $this->getRequest()->setRouteName('catalog');
        $page = parent::execute();
        return $page;
    }
}

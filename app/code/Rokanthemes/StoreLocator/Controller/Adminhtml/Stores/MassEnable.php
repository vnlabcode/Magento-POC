<?php
/**
 * Copyright © 2019 Rokanthemes. All rights reserved.
 */

namespace Rokanthemes\StoreLocator\Controller\Adminhtml\Stores;

use \Rokanthemes\StoreLocator\Controller\Adminhtml\MassAction;
use \Magento\Framework\Controller\ResultFactory;
use \Magento\Framework\App\ResponseInterface;

class MassEnable extends MassAction
{

    public function execute()
    {
        $collection = $this->filter->getCollection($this->storeCollectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection as $store) {
            $store->setIsActive(true);
            $this->storeRepository->save($store);
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 store(s) have been enabled.', $collectionSize));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}

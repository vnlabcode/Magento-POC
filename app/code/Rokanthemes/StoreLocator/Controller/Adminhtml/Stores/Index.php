<?php
/**
 * Copyright © 2019 Rokanthemes. All rights reserved.
 */

namespace Rokanthemes\StoreLocator\Controller\Adminhtml\Stores;

use \Rokanthemes\StoreLocator\Controller\Adminhtml\Stores;

class Index extends Stores
{

    public function execute()
    {
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()->prepend(__('Store Locator - Stores'));

        return $resultPage;
    }
}

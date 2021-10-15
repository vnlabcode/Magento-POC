<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Rokanthemes\CategoryTab\Controller\Category;

/**
 * View a category on storefront. Needs to be accessible by POST because of the store switching.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class View extends \Magento\Catalog\Controller\Category\View
{
    public function execute()
    {
        $this->getRequest()->setRouteName('catalog')
            ->setActionName('view')
            ->setControllerName('category');
        $result = parent::execute();
        if($this->getRequest()->isXmlHttpRequest())
        {
            $layout = $result->getLayout();
            $block = $layout->getBlock('category.products.list');
            $block->setTemplate('Rokanthemes_CategoryTab::product/list.phtml');
            $response = $block->toHtml();
            $this->getResponse()->setBody($response);
            return '';
        }
        return $result;
    }
}

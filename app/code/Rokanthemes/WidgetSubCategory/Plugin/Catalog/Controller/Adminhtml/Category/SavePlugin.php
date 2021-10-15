<?php
namespace Rokanthemes\WidgetSubCategory\Plugin\Catalog\Controller\Adminhtml\Category;

use Magento\Catalog\Controller\Adminhtml\Category\Save as SaveController;
use Rokanthemes\WidgetSubCategory\Controller\Adminhtml\Category\Thumbnail\Upload as ThumbnailUpload;

class SavePlugin
{
    /**
     * Add additional images
     *
     * @param SaveController $subject
     * @param array $data
     * @return array
     */
    public function beforeImagePreprocessing(SaveController $subject, $data)
    {
        foreach ($this->getAdditionalImages() as $imageType) {
            if (empty($data[$imageType])) {
                unset($data[$imageType]);
                $data[$imageType]['delete'] = true;
            }
        }

        return [$data];
    }

    /**
     * Get additional Images
     *
     * @return array
     */
    protected function getAdditionalImages() {
        return [
            ThumbnailUpload::CATEGORY_ATTRIBUTE_IMAGE
        ];
    }
}

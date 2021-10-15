<?php
namespace Rokanthemes\CustomMenu\Model\Category;
 
use Magento\Framework\Registry;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Category\DataProvider as CategoryDataProvider;
use Magento\Store\Model\Store;
use Rokanthemes\CustomMenu\Helper\Category as CategoryHelper;

class DataProvider
{
    protected $registry;

    protected $request;

    private $categoryFactory;

    private $categoryHelper;

    private $requestFieldName = 'id';

    private $requestScopeFieldName = 'store';

    public function __construct(
        Registry $registry,
        RequestInterface $request,
        CategoryFactory $categoryFactory,
        CategoryHelper $categoryHelper
    ) {
        $this->registry = $registry;
        $this->request = $request;
        $this->categoryFactory = $categoryFactory;
        $this->categoryHelper = $categoryHelper;
    }

    public function afterGetData(CategoryDataProvider $subject, $result)
    {
        $category = $this->getCurrentCategory();

        if ($category && $category->getId()) {
            $categoryData = $result[$category->getId()];
            foreach ($this->getAdditionalImageTypes() as $imageType) {
                if (isset($categoryData[$imageType])) {
                	if(is_string($categoryData[$imageType])){
                		$data_cat = $categoryData[$imageType];
	                    unset($categoryData[$imageType]);
                	}
                    elseif(is_array($categoryData[$imageType])){
                        $data_cat = $categoryData[$imageType];
                        if(isset($_SERVER['SCRIPT_NAME']) && isset($data_cat[0]['url'])){
                            $sub_folder = str_replace("index.php","",$_SERVER['SCRIPT_NAME']);
                            $sub_folder = str_replace("/","",$sub_folder);
                            $url_load = (string)$data_cat[0]['url'];
                            if($sub_folder != '' && !preg_match("/{$sub_folder}/i", $url_load)){
                                $data_cat[0]['url'] = '/'.$sub_folder.$data_cat[0]['url'];
                                $categoryData[$imageType] = $data_cat;
                            }
                        }
                    }
                }
            }

            $result[$category->getId()] = $categoryData;
        }

        return $result;
    }

    private function getCurrentCategory()
    {
        $category = $this->registry->registry('category');

        if ($category) {
            return $category;
        }

        $requestId = $this->request->getParam($this->requestFieldName);
        $requestScope = $this->request->getParam($this->requestScopeFieldName, Store::DEFAULT_STORE_ID);

        if ($requestId) {
            $category = $this->categoryFactory->create();
            $category->setStoreId($requestScope);
            $category->load($requestId);
            if (!$category->getId()) {
                throw NoSuchEntityException::singleField('id', $requestId);
            }
        }

        return $category;
    }

    private function getAdditionalImageTypes()
    {
        return [
            'image',
            'rt_menu_icon_img',
            'rt_menu_bg_img',
            'cat_image_thumbnail',
            'vc_menu_icon_img'
        ];
    }
}
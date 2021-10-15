<?php
namespace Rokanthemes\ProductTab\Plugin\Catalog\Block\Product;

use Magento\Catalog\Block\Product\Image as ImageBlock;
use Magento\Catalog\Model\View\Asset\ImageFactory as AssetImageFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Image\ParamsBuilder;
use Magento\Catalog\Model\View\Asset\PlaceholderFactory;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\ConfigInterface;
use Magento\Catalog\Helper\ImageFactory as ImageHelper;

class ListProduct
{
    /**
     * @var ConfigInterface
     */
    private $presentationConfig;

    /**
     * @var AssetImageFactory
     */
    private $viewAssetImageFactory;

    /**
     * @var ParamsBuilder
     */
    private $imageParamsBuilder;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var PlaceholderFactory
     */
    private $viewAssetPlaceholderFactory;
    private $imageHelper;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param ConfigInterface $presentationConfig
     * @param AssetImageFactory $viewAssetImageFactory
     * @param PlaceholderFactory $viewAssetPlaceholderFactory
     * @param ParamsBuilder $imageParamsBuilder
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        ConfigInterface $presentationConfig,
        AssetImageFactory $viewAssetImageFactory,
        PlaceholderFactory $viewAssetPlaceholderFactory,
        ParamsBuilder $imageParamsBuilder,
        ImageHelper $imageFactory
    ) {
        $this->objectManager = $objectManager;
        $this->presentationConfig = $presentationConfig;
        $this->viewAssetPlaceholderFactory = $viewAssetPlaceholderFactory;
        $this->viewAssetImageFactory = $viewAssetImageFactory;
        $this->imageParamsBuilder = $imageParamsBuilder;
        $this->imageHelper = $imageFactory;
    }
    public function aroundGetImage(\Magento\Catalog\Block\Product\ListProduct $productList, \Closure $proceed, $product, $imageId, $attributes = [])
    {
        if($imageId == 'product_tab_product_image')
        {
            if(!$typeImg = $productList->getRequest()->getParam('imgT'))
                $typeImg = 'small_image';
            return $this->getProductTabProductImage($productList, $product, $typeImg, $attributes);
        }elseif ($imageId == 'product_tab_product_image_hover')
        {
            if(!$typeImg = $productList->getRequest()->getParam('imgHover'))
                $typeImg = 'small_image';
            return $this->getProductTabProductImage($productList, $product, $typeImg, $attributes);
        }
        return $proceed($product, $imageId, $attributes);
    }
    public function getProductTabProductImage($productList, $product, $typeImg, $attributes = [])
    {
        if(!$height = $productList->getRequest()->getParam('imgH'))
            $height = 300;
        if(!$width = $productList->getRequest()->getParam('imgW'))
            $width = 240;
        $viewImageConfig = ['type' => $typeImg, 'width' => $width, 'height' => $height];
        $imageMiscParams = $this->imageParamsBuilder->build($viewImageConfig);
        $originalFilePath = $product->getData($imageMiscParams['image_type']);

        if ($originalFilePath === null || $originalFilePath === 'no_selection') {
            $imageAsset = $this->viewAssetPlaceholderFactory->create(
                [
                    'type' => $imageMiscParams['image_type']
                ]
            );
        } else {
            $imageAsset = $this->viewAssetImageFactory->create(
                [
                    'miscParams' => $imageMiscParams,
                    'filePath' => $originalFilePath,
                ]
            );
        }

        $attributes = $attributes === null ? [] : $attributes;

        $data = [
            'data' => [
                'template' => 'Magento_Catalog::product/image_with_borders.phtml',
                'image_url' => $this->resizeImage($product, $typeImg, $width, $height),
                'width' => $imageMiscParams['image_width'],
                'height' => $imageMiscParams['image_height'],
                'label' => $this->getLabel($product, $imageMiscParams['image_type']),
                'ratio' => $this->getRatio($imageMiscParams['image_width'], $imageMiscParams['image_height']),
                'custom_attributes' => $this->getStringCustomAttributes($attributes),
                'class' => $this->getClass($attributes),
                'product_id' => $product->getId()
            ],
        ];

        return $this->objectManager->create(ImageBlock::class, $data);
    }
    public function resizeImage($product, $typeImage, $width, $height)
    {
        $imageHelper = $this->imageHelper->create();
        $imageHelper->init($product, $typeImage)->setImageFile($product->getData($typeImage))->resize($width, $height);
        return $imageHelper->getUrl();
    }
    /**
 * Retrieve image custom attributes for HTML element
 *
 * @param array $attributes
 * @return string
 */
    private function getStringCustomAttributes(array $attributes): string
    {
        $result = [];
        foreach ($attributes as $name => $value) {
            if ($name != 'class') {
                $result[] = $name . '="' . $value . '"';
            }
        }
        return !empty($result) ? implode(' ', $result) : '';
    }

    /**
     * Retrieve image class for HTML element
     *
     * @param array $attributes
     * @return string
     */
    private function getClass(array $attributes): string
    {
        return $attributes['class'] ?? 'product-image-photo';
    }

    /**
     * Calculate image ratio
     *
     * @param int $width
     * @param int $height
     * @return float
     */
    private function getRatio(int $width, int $height): float
    {
        if ($width && $height) {
            return $height / $width;
        }
        return 1.0;
    }

    /**
     * Get image label
     *
     * @param Product $product
     * @param string $imageType
     * @return string
     */
    private function getLabel(Product $product, string $imageType): string
    {
        $label = $product->getData($imageType . '_' . 'label');
        if (empty($label)) {
            $label = $product->getName();
        }
        return (string) $label;
    }
}

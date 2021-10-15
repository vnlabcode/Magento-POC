<?php
/**
 * Copyright © 2019 Rokanthemes. All rights reserved.
 */

namespace Rokanthemes\StoreLocator\Block\Adminhtml\Stores\Helper;

use \Magento\Framework\Data\Form\Element\AbstractElement;
use \Magento\Framework\Data\Form\Element\Factory;
use \Magento\Framework\Data\Form\Element\CollectionFactory;
use \Magento\Framework\Escaper;
use \Rokanthemes\StoreLocator\Helper\Config as ConfigHelper;

class GoogleMap extends AbstractElement
{
    /**
     * @var \Rokanthemes\StoreLocator\Helper\Config
     */
    private $configHelper;

    /**
     * @param Factory              $factoryElement
     * @param CollectionFactory    $factoryCollection
     * @param Escaper              $escaper
     * @param ConfigHelper         $configHelper
     * @param array                $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        ConfigHelper $configHelper,
        array $data = []
    ) {
        $this->configHelper = $configHelper;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
    }

    /**
     * Return the element as HTML
     *
     * @return string
     */
    public function getElementHtml()
    {
        $googleApiKey = $this->configHelper->getGoogleApiKeyFrontend(); 
        $this->addClass('google-map admin__control-google-map');
        $html = '<script src="https://maps.googleapis.com/maps/api/js?key=' . $googleApiKey . '"></script>';
        $html .= '<div id="google-map-container" style="width: 100%; height: 400px;"></div>';
        $html .= $this->getAfterElementHtml();

        return $html;
    }
}

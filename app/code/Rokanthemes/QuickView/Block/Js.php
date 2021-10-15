<?php
namespace Rokanthemes\QuickView\Block;

use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\View\Element\Template\Context;

class Js extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'default.phtml';

    protected $customerSession;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->customerSession = $customerSession;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerSession->getCustomer()->getId();
    }

    /**
     * @param string $group
     * @return mixed
     */
    public function getSettingStatus($group = 'general')
    {
        return $this->_scopeConfig->getValue('quickview/' . $group . '/enabled');
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getConfigValue($path)
    {
        return $this->_scopeConfig->getValue($path);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getModuleConfigValue($path)
    {
        return $this->_scopeConfig->getValue('quickview/' . $path);
    }
}

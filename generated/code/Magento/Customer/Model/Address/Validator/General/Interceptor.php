<?php
namespace Magento\Customer\Model\Address\Validator\General;

/**
 * Interceptor class for @see \Magento\Customer\Model\Address\Validator\General
 */
class Interceptor extends \Magento\Customer\Model\Address\Validator\General implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Eav\Model\Config $eavConfig, \Magento\Directory\Helper\Data $directoryData)
    {
        $this->___init();
        parent::__construct($eavConfig, $directoryData);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(\Magento\Customer\Model\Address\AbstractAddress $address)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        return $pluginInfo ? $this->___callPlugins('validate', func_get_args(), $pluginInfo) : parent::validate($address);
    }
}

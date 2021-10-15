<?php

namespace Rokanthemes\Themeoption\Observer;

use Magento\Framework\Event\ObserverInterface;

class Activationcode implements ObserverInterface
{
    protected $_messageManager;

    private $_verifypurchasecode;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Rokanthemes\Themeoption\Helper\Verifypurchasecode $verifypurchasecode
    ) {
       $this->_messageManager = $messageManager;
       $this->_verifypurchasecode = $verifypurchasecode;
    }

    /**
     * Log out user and redirect to new admin custom url
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.ExitExpression)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $verify_envato_purchase_code = $this->_verifypurchasecode->verifyEnvatoPurchaseCode();
        if(is_array($verify_envato_purchase_code)){
            if(isset($verify_envato_purchase_code['result']) && $verify_envato_purchase_code['result'] == 'success'){
                $this->_messageManager->getMessages(true);
                $this->_messageManager->addSuccess(__('Theme successfully activated using manual activation. Thanks for buying our theme.'));
            }
            elseif(isset($verify_envato_purchase_code['msg'])){
                $this->_messageManager->getMessages(true);
                $this->_messageManager->addError(__($verify_envato_purchase_code['msg']));
            }
        }
        elseif($verify_envato_purchase_code == 5){
            $this->_messageManager->getMessages(true);
            $this->_messageManager->addSuccess(__('You are using localhost, so no need use purchase code.'));
        }
    }
}

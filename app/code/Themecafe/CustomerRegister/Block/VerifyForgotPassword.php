<?php
namespace Themecafe\CustomerRegister\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Message\ManagerInterface;

class VerifyForgotPassword extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        Context $context,
        Session $customerSession,
        Http $http,
        ManagerInterface $messageManager
    ) {
        $this->customerSession = $customerSession;
//        $baseUrl = $context->getStoreManager()->getStore()->getBaseUrl();
//        echo $data->isActive();die;
//        if(!$data->isActive()){
//            $http->setRedirect($baseUrl.'cms/noroute/index/');
//        }
//        if(!$customerSession->getData('forgotPasswordContact')) {
//            $messageManager->addError(__('Your password reset OTP has expired.'));
//            $http->setRedirect($baseUrl.'customer/account/forgotpassword/');
//        }
//        $http->setRedirect($baseUrl.'cms/noroute/index/');
        parent::__construct($context);
    }

}
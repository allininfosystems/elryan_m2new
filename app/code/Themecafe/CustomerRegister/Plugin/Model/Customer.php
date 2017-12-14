<?php
namespace Themecafe\CustomerRegister\Plugin\Model;

use Magento\Framework\App\ObjectManager;

class Customer {
    public function beforeLoadByEmail(\Magento\Customer\Model\Customer $subject, $customerEmail) {
        $objectManager = ObjectManager::getInstance();
        $themecafeHelper = $objectManager->get('Themecafe\CustomerRegister\Helper\Data');
        if($themecafeHelper->isActive()){
            $customerFactory = $objectManager->create('Magento\Customer\Model\CustomerFactory');
            $customer = $customerFactory->create()->getCollection()->addFieldToFilter('themecafe_mobile', $customerEmail);
            $var = $customer->getData();
            if(!empty($var)){
                    $customerEmail = $var[0]['email'];
            }
            return [$customerEmail];	
        }
        return $customerEmail;
    }
}

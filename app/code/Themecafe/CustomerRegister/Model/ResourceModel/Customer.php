<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Themecafe\CustomerRegister\Model\ResourceModel;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Validator\Exception as ValidatorException;
use Magento\Framework\Exception\InputException;

/**
 * Customer entity resource model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Customer extends \Magento\Customer\Model\ResourceModel\Customer
{
    protected function _beforeSave(\Magento\Framework\DataObject $customer)
    {
	parent::_beforeSave($customer);
	$objectManager = ObjectManager::getInstance();
        
	$isactive = $objectManager->get('Themecafe\CustomerRegister\Helper\Data')->isActive();
        if($isactive){
            $state = $objectManager->get('Magento\Framework\App\State');
        
            if ($state->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML) {
                $request = $objectManager->get('Magento\Framework\App\RequestInterface');
                $themecafeVerify = 0;
                if($request->getParam('customer')['themecafe_mobile_verify'] == "true"){
                    $themecafeVerify = 1;
                }
                $customer->setData('themecafe_mobile_verify',$themecafeVerify);
            }
            $websiteid = $objectManager->create('Magento\Store\Model\StoreManagerInterface')->getStore()->getWebsiteId();
            $messageManager = $objectManager->create('Magento\Framework\Message\ManagerInterface');

            $customerObj = $objectManager->create('Magento\Customer\Model\CustomerFactory')->create()->getCollection()
                            ->addAttributeToFilter('themecafe_mobile',trim($customer->getThemecafeMobile()))
                            ->addAttributeToFilter('entity_id', array('neq' => $customer->getId()));
            
            if ($customer->getSharingConfig()->isWebsiteScope()) {
                $customerObj->addFieldToFilter('website_id', $websiteid);
            }

            $urlFactor = $objectManager->create('Magento\Framework\UrlFactory')->create();
            if($customer->getThemecafeMobile()){
                if (!ctype_digit($customer->getThemecafeMobile())) {
                    $message = __('Character(s) should not be allowed in mobile number.');
                    throw new ValidatorException($message);
                }
                if ($state->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML && $customerObj->count()){
                    throw new InputException(
                        __('This mobile number is already registered, try another mobile number or "login".')
                    );
                }
            }   
            try{
                if ($customerObj->count() && !$customer->getId()) {
                    $url = $urlFactor->getUrl('customer/account/forgotpassword');
                    $message = __(
                        'There is already an account with this mobile number. If you are sure that it is your mobile number, <a href="%1" >click here </a> to get your password and access your account.',
                        $url
                    );
                    throw new \Exception($message);
                }
            }catch(\Exception $e){
                $messageManager->addError($e->getMessage());
                throw new \Exception($e);
            }
        }
    }
}

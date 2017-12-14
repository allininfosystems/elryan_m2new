<?php

namespace Themecafe\CustomerRegister\Controller\Frontend;

use Magento\Framework\Exception\InputException;
use Magento\Customer\Api\AccountManagementInterface;

class OtpSend extends \Magento\Framework\App\Action\Action {

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $_customerSession,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\Stdlib\DateTime\DateTime $DateTime,
        \Themecafe\CustomerRegister\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeInterface,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\UrlFactory $urlFactory,
        AccountManagementInterface $customerAccountManagement
    ) {
        $this->context = $context;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->om = $context->getObjectManager();
        $this->_customerSession = $_customerSession;
        $this->_resources = $resourceConnection;
        $this->DateTime = $DateTime;
        $this->helper = $helper;
        $this->_storeInterface = $storeInterface;
        $this->messageManager = $context->getMessageManager();
        $this->request = $context->getRequest();
        $this->customerFactory = $customerFactory;
        $this->urlFactory = $urlFactory->create();
        $this->customerAccountManagement = $customerAccountManagement;
        parent::__construct($context);
    }
    public function execute() {
        $otpcode = $this->generateOtpCode();
        $timestamp = $this->DateTime->timestamp();
        $storeId = $this->_storeInterface->getStore()->getId();
        if($this->request->getParam('forgotpasswordverification')){
            $email = $this->_customerSession->getData('forgotPasswordEmail');
            $this->customerAccountManagement->initiatePasswordReset(
                $email,
                \Magento\Customer\Model\AccountManagement::EMAIL_RESET
            );
            $response['messages'] = '<div class="message-success success message"><div>'.__('OTP Successfully sent to your email and mobile number').'</div></div>';
            $response['success'] = 1;
        }else{
            $themecafeMobilenumber = $this->request->getParam('themecafeMobilenumber');
            $this->_customerSession->setData('themecafeMobilenumber', $themecafeMobilenumber);
            $this->_customerSession->setData('timestamp', $timestamp);
            $this->_customerSession->setData('otpcode', $otpcode);
            $this->_customerSession->setData('mobile_verified',false);
            //$message = str_replace("##OTP##", $otpcode,  $this->helper->getConfig('themecafe_customer_verification/otp_label_message/otp_message'));
	    if($this->request->getParam('customerEdit')){
                $otpMessage = $this->helper->getConfig('themecafe_customer_verification/otp_label_message/registered_otp_message');
            }
            else{
                $otpMessage = $this->helper->getConfig('themecafe_customer_verification/otp_label_message/otp_message');
            }
	    $message = str_replace("##OTP##", $otpcode, $otpMessage);
            if(trim($this->helper->otpMessage($storeId))==""){
                $message = $otpcode;
            }
            /*if(trim($message)==""){
                $message = $otpcode;
            }*/
        }
        try {
            if(!$this->request->getParam('forgotpasswordverification')){
                if($this->uniqueCheck()){
                    $response['messages'] = '<div class="message-error error message"><div>'.$this->uniqueCheck().'</div></div>';
                }
                else{
                    $response['messages'] = '<div class="message-error error message"><div>'.$this->helper->sendErrorMessage().'</div></div>';
                    if($themecafeMobilenumber){
                        $this->helper->sendSms($themecafeMobilenumber, $message, 'ALL', $storeId);    
                        if($this->helper->getQueryStringElement() && !$this->uniqueCheck()) {
                            $response['messages'] = '<div class="message-success success message"><div>'.$this->helper->sendSuccessMessage().'</div></div>';
                            $response['success'] = 1;
                        }
                    }
                    else{
                        $response['messages'] = '<div class="message-success success message"><div>'.__('OTP Successfully sent to your email').'</div></div>';
                        $response['success'] = 1;
                    }
                }                
            }
        } catch (\RuntimeException $e) {
            $this->messageManager->addError(__('Problem while sending your requestion. %1', $e->getMessage()));
        }
        
        $this->getResponse()->representJson(
            $this->om->get('Magento\Framework\Json\Helper\Data')->jsonEncode($response)
        );
        return ;
    }
    public function generateOtpCode() {
        $string = '0123456789';
        $string_shuffled = str_shuffle($string);
        return substr($string_shuffled, 1, 4);
//	return 1234;
    }
    public function uniqueCheck() {
        $currentCustomerId = $this->_customerSession->getCustomer()->getId();
        $cn = $this->request->getParam('themecafeMobilenumber');
        $websiteid = $this->_storeInterface->getStore()->getWebsiteId();
        $customerObj = $this->customerFactory->create()->getCollection()
                        ->addAttributeToFilter('themecafe_mobile',trim($cn));
//                        ->addAttributeToFilter('entity_id', array('neq' => $currentCustomerId));
        $websiteScope = $this->helper->getConfig('customer/account_share/scope');
        if ($websiteScope) {
            $customerObj->addFieldToFilter('website_id', $websiteid);
        }
        if($currentCustomerId){
            $message = __('There is already an account with this mobile number.');
        }
        else{
//        $urlFactor = $this->om->create('Magento\Framework\UrlFactory')->create();
            $url = $this->urlFactory->getUrl('customer/account/forgotpassword');
            $message = __(
                'There is already an account with this mobile number. If you are sure that it is your mobile number, <a href="%1" >click here </a> to get your password and access your account.',
                $url
            );
        }
        $return = 0;
        if ($customerObj->count()){
            $return = $message;
        }
        return $return;
    }
}

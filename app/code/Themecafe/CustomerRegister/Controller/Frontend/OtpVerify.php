<?php

namespace Themecafe\CustomerRegister\Controller\Frontend;

class OtpVerify extends \Magento\Framework\App\Action\Action {
    public $timeLimit = 5 * 60;//5 minutes difference

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $_customerSession,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\Stdlib\DateTime\DateTime $DateTime,
        \Themecafe\CustomerRegister\Helper\Data $helper
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->om = $context->getObjectManager();
        $this->_customerSession = $_customerSession;
        $this->_resources = $resourceConnection;
        $this->DateTime = $DateTime;
        $this->request = $context->getRequest();
        parent::__construct($context);
    }
    public function execute() {

        $otpvalue = $this->request->getParam('otpvalue');
        
        $currentTimestamp = $this->DateTime->timestamp();
        
        if($this->request->getParam('forgotpasswordverification')){
            $contactnumber = $this->_customerSession->getData('forgotPasswordContact');
        }
        else{
            $contactnumber = $this->request->getParam('themecafeMobilenumber');
        }
        
        $return = $this->verifyOTP($otpvalue, $currentTimestamp, $contactnumber);
        
        $response['response'] = $return;
        $response['contactnumber'] = $contactnumber;
//        echo $this->_customerSession->getData('passwordResetLink');
        if($this->request->getParam('forgotpasswordverification') && $return){    
            $response['redirect'] = $this->_customerSession->getData('passwordResetLink');
        }
        $this->getResponse()->representJson(
            $this->om->get('Magento\Framework\Json\Helper\Data')->jsonEncode($response)
        );
        return ;
    }
    public function verifyOTP($otpvalue, $currentTimestamp, $contactnumber) {
        if($this->request->getParam('forgotpasswordverification')){
            $sessionmobilenumber = $this->_customerSession->getData('forgotpasswordthemecafeMobilenumber');
            
            $sessionTimestamp = $this->_customerSession->getData('forgotpasswordtimestamp') + $this->timeLimit;
            $sessionotpcode = $this->_customerSession->getData('forgotpasswordotpcode');
//            echo $sessionotpcode."\n";
//            echo  $otpvalue."\n"; echo $currentTimestamp."\n"; echo $sessionTimestamp."\n"; echo $sessionmobilenumber."\n";
//            echo $contactnumber."\n";
            if(($sessionotpcode == $otpvalue) && ($currentTimestamp < $sessionTimestamp) && ($sessionmobilenumber == $contactnumber)){
                $return = 1;
                $this->_customerSession->setData('forgotpasswordmobile_verified',true);
            }else {
                $return = 0;
                $this->_customerSession->setData('forgotpasswordmobile_verified',false);
            }
            return $return;
        }
        else{
            $sessionmobilenumber = $this->_customerSession->getData('themecafeMobilenumber');
            $sessionTimestamp = $this->_customerSession->getData('timestamp') + $this->timeLimit;
            $sessionotpcode = $this->_customerSession->getData('otpcode');
            if(($sessionotpcode == $otpvalue) && ($currentTimestamp < $sessionTimestamp) && ($sessionmobilenumber == $contactnumber)){
                $return = 1;
                $this->_customerSession->setData('mobile_verified',true);
            }else {
                $return = 0;
                $this->_customerSession->setData('mobile_verified',false);
            }
            return $return;
        }
    }

}

<?php

/**
 * Copyright © 2015 Themecafe Design. All rights reserved.
 * See COPYING.txt for license details.
 */
// @codingStandardsIgnoreFile

namespace Themecafe\CustomerRegister\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    const XML_PATH_ACTIVE = 'themecafe_customer_verification/general/active';

    const XML_PATH_RECORD_LOGS = 'themecafe_customer_verification/sms_setting/record_logs';
    const XML_PATH_URL = 'themecafe_customer_verification/sms_setting/url';
    const XML_PATH_GET_POST = 'themecafe_customer_verification/sms_setting/get_post';
    const XML_PATH_PARAMS = 'themecafe_customer_verification/sms_setting/params';
    const XML_PATH_LOGIN = 'themecafe_customer_verification/sms_setting/login';
    const XML_PATH_PASSWORD = 'themecafe_customer_verification/sms_setting/password';
    
    const XML_PATH_SENDER = 'themecafe_customer_verification/sms_setting/sender';
    const XML_PATH_NUMBER_FORMAT = 'themecafe_customer_verification/sms_setting/number_format';
    const XML_PATH_REPLACE = 'themecafe_customer_verification/sms_setting/replace_instruction';
    
    const XML_PATH_OTP_MESSAGE = 'themecafe_customer_verification/otp_label_message/otp_message';
    const XML_PATH_OTP_LINKTITLE = 'themecafe_customer_verification/otp_label_message/link_title';
    const XML_PATH_OTP_TITLELABEL = 'themecafe_customer_verification/otp_label_message/title_label';
    const XML_PATH_OTP_SEND_ERROR_MESSAGE = 'themecafe_customer_verification/otp_label_message/send_error_message';
    const XML_PATH_OTP_SEND_SUCCESS_MESSAGE = 'themecafe_customer_verification/otp_label_message/send_success_message';
    

    protected $_scopeConfig, $_storeManager, $coreData, $_orderFactory, $_orderRepository;
    protected $moduleManager;
    private  $customerViewHelper, $customerRegistry, $_dateTime;
    protected $currentCustomer = null;
    private $session;
    protected $inlineTranslation;
    private $customerFactory;
    protected $addressFactory;
    protected $_appEmulation;
    protected $_transportBuilder;
    protected $logger;
    protected $_tablecellCollection;
    protected $query_string;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context, 
        \Magento\Store\Model\StoreManagerInterface $storeManager, 
        \Magento\Sales\Model\OrderFactory $orderFactory, 
        \Magento\Sales\Model\OrderRepository $orderRepository, 
        \Magento\Framework\Pricing\Helper\Data $coreData,
        \Magento\Framework\Stdlib\DateTime $dateTime, 
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder, 
        \Magento\Customer\Helper\View $customerViewHelper, 
        \Magento\Customer\Model\CustomerRegistry $customerRegistry, 
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer, 
        \Magento\Customer\Model\Session $session, 
        \Magento\Customer\Model\CustomerFactory $customerFactory, 
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Magento\Store\Model\App\Emulation $appEmulation, 
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Themecafe\CustomerRegister\Model\ResourceModel\Cell\Tablecell\CollectionFactory $tablecell
    ) {
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_storeManager = $storeManager;

        $this->_orderFactory = $orderFactory;
        $this->_orderRepository = $orderRepository;
        
        $this->coreData = $coreData;

        $this->moduleManager = $context->getModuleManager();
        $this->session = $session;
        $this->customerFactory = $customerFactory;
        $this->addressFactory = $addressFactory;

        $this->_appEmulation = $appEmulation;
        $this->_transportBuilder = $transportBuilder;

        $this->inlineTranslation = $inlineTranslation;

        $this->_dateTime = $dateTime;
        $this->customerViewHelper = $customerViewHelper;
        $this->customerRegistry = $customerRegistry;
        $this->currentCustomer = $currentCustomer;
        
        $this->_tablecellCollection = $tablecell;

        parent::__construct($context);
    }
    public function getConfig($xml) {
        return $this->_scopeConfig->getValue($xml, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    public function getStoreId(){
        return $this->_storeManager->getStore()->getId();
    }
    public function isActive($storeId = null) {
        return $this->_scopeConfig->getValue(self::XML_PATH_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
    
    public function canRecordLogs($storeId = null) {
        return $this->_scopeConfig->getValue(self::XML_PATH_RECORD_LOGS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
    
    public function gatewayUrl($storeId = null) {
        return $this->_scopeConfig->getValue(self::XML_PATH_URL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
    
    public function httpMethod($storeId = null) {
        return $this->_scopeConfig->getValue(self::XML_PATH_GET_POST, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function gatewayParams($storeId = null) {
        return $this->_scopeConfig->getValue(self::XML_PATH_PARAMS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
    
    public function getewayLogin($storeId = null) {
        return $this->_scopeConfig->getValue(self::XML_PATH_LOGIN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
    public function getewayPassword($storeId = null) {
        return $this->_scopeConfig->getValue(self::XML_PATH_PASSWORD, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
    public function originSender($storeId = null) {
        return $this->_scopeConfig->getValue(self::XML_PATH_SENDER, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }    
    public function smsNumberFormat($storeId = null) {
        return $this->_scopeConfig->getValue(self::XML_PATH_NUMBER_FORMAT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
    public function replaceRegEx($storeId = null) {
        return $this->_scopeConfig->getValue(self::XML_PATH_REPLACE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
    public function otpMessage($storeId = null) {
        return __($this->_scopeConfig->getValue(self::XML_PATH_OTP_MESSAGE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId));
    }
    public function titleLabel($storeId = null) {
        return __(trim($this->_scopeConfig->getValue(self::XML_PATH_OTP_TITLELABEL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId)));
    }
    public function sendErrorMessage($storeId = null) {
        return __(trim($this->_scopeConfig->getValue(self::XML_PATH_OTP_SEND_ERROR_MESSAGE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId)));
    }
    public function sendSuccessMessage($storeId = null) {
        return __(trim($this->_scopeConfig->getValue(self::XML_PATH_OTP_SEND_SUCCESS_MESSAGE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId)));
    }
    
    public function recordLog($message_log)
    {
        // log exception to exceptions log
        $message = sprintf('Log message: %s',$message_log);
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/themecafe_customer_verification.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($message);

    }
    
    protected function setQueryStringElement($query_str){
        $this->query_string = $query_str;
    }
    
    public function getQueryStringElement(){
        return $this->query_string;
    }
    
    public function sendSms($rcpt, $message, $customer_country_code, $storeId = null){
        $record_log = $this->canRecordLogs($storeId);
        
        if ($record_log){
            $this->recordLog("[[ Instanciate Message sending with the following elements: RCPT : $rcpt, MESSAGE : $message, COUNTRY : $customer_country_code, STORE_ID : $storeId ]]\r\n");
        }
 
	if ($url = $this->gatewayUrl($storeId)){
            
            $submit_method = $this->httpMethod($storeId);

            $params = $this->gatewayParams($storeId);
            $user = $this->getewayLogin($storeId);
            $pass = $this->getewayPassword($storeId);
            $sender = $this->originSender($storeId);
            $allowed_countries = 'ALL';
            $number_format = $this->smsNumberFormat($storeId);
            $pattern_replacement = $this->replaceRegEx($storeId);
            
            if ($number_format == 0){
                $sender = str_replace('+', '', $sender);
            } else if (preg_match("/\+/i", $sender)){
                $sender = '+'.$sender;
            }
            
            $params = str_replace('{{user}}', $user, $params);
            $params = str_replace('{{pass}}', $pass, $params);

            $rcpt = str_replace('-', '', $rcpt);
            $rcpt = str_replace('.', '', $rcpt);
            $rcpt = str_replace(' ', '', $rcpt);
            $rcpt = str_replace('(', '', $rcpt);
            $rcpt = str_replace(')', '', $rcpt);
            
            if (!in_array($customer_country_code, explode(',',$allowed_countries)) && $customer_country_code != 'ALL'){
                if ($record_log){
            	    $this->recordLog("[[ UNABLE to send for customer country code $customer_country_code. ALLOWED Countries are $allowed_countries ]]\r\n");
        	} 
		return false;
            }
            
            $rcpt = preg_replace($pattern_replacement,'', $rcpt); 
            
            $tablecellsCollection = $this->_tablecellCollection->create();
            $tablecellsCollection->setCountryFilter($customer_country_code);
            
            $tablecellsCollection->load();
            
            $can_send = false;
            $message_log = "";
            
            foreach ($tablecellsCollection->getItems() as $item){
                
                $pattern = $item->getData('regex_mask');
                $replace = $item->getData('international_code');
                $mobile_code = $item->getData('mobile_code');
                
                if ($pattern && $replace && $mobile_code){
                    $pattern = str_replace('(d', '(\d', $pattern);
                    $pattern = str_replace('(+','(\+',$pattern);
                    $pattern = str_replace('|+','|\+',$pattern);
                    $patterns = array ($pattern);
                    $replace = array ($replace.$mobile_code.'${2}');
                    $rcpt = trim($rcpt);
                    
                    if ($record_log){
                        $message_log .= "[[ php instruction: preg_match(\"$pattern\", \"$rcpt\") -- ";
                        $message_log .= "mobile code: $mobile_code ]]\r\n";
                    }
                    if (preg_match($pattern, $rcpt)){  
                        
                        if ($record_log){
                            $message_log .= "[[ pattern found: $pattern -- ";
                            $message_log .= 'php instruction: preg_replace(array("'.$pattern.'"), array("'.$replace[0].'"), "'.$rcpt.'") -- ';
                            $message_log .= "mobile code: $mobile_code ]]\r\n";
                        }
                        $rcpt = preg_replace($patterns, $replace, $rcpt);
                        
                        if ($record_log){
                            $message_log .= "[[ rcpt after preg_replace: $rcpt]]\r\n";
                        }
                        
                        $can_send = true;
                        break;
                    }
                    
                }
            }

            if ($message_log == "" && !$can_send){
                if ($record_log){
                    $message_log .= "[[ pattern not found for mobile number: $rcpt ]]\r\n";
                }
            }
            
            if ($record_log && $message_log){
                $this->recordLog($message_log);
            }
            
            if($can_send){
                //send sms
                if ($number_format == 0){
                    $rcpt = str_replace('+', '', $rcpt);
                }
                else if($number_format == 2){
                    $replace = $item->getData('international_code');
                    $pattern = str_replace('+','',$replace);
                    $rcptnew = str_replace('+', '', $rcpt);
//                    $rcpt = str_replace($replace, '', $rcpt);
                    $ptn = "/^$pattern/";
                    $rpltxt = ""; 
                    $rcpt =  preg_replace($ptn, $rpltxt, $rcptnew);
                }
                
                $params = str_replace('{{phone_number}}', $rcpt, $params);

                $text_message = strip_tags($message);


                $params = str_replace('{{text_message}}', $text_message, $params);
                $params = str_replace('{{sender}}', $sender, $params);

		if ($record_log){
                    $message_log .= "[[ PROCESSING SMS operations with the following elements: \r\n COUNTRY : $customer_country_code - ALLOWED COUNTRIES : $allowed_countries \r\n PARAM : $params \r\n Message : $text_message \r\n Phone : $rcpt ]]\r\n";
                }
 
                $arr_params = explode('&',$params);
                $temp = array();
                foreach ($arr_params as $value){
                    $arr_val = explode("=", $value);
                    if (isset($arr_val[0]) && isset($arr_val[1]))
                        $temp[] = $arr_val[0].'='.urlencode($arr_val[1]);
                }
                $params = join('&', $temp);
                
                $this->setQueryStringElement($url.'?'.$params);
//$this->setQueryStringElement(1);
                
                if ($record_log){
                    $this->recordLog("SMS Gateway call: $url?$params");
                }
                
                $curl_session = curl_init();
                curl_setopt($curl_session,CURLOPT_URL,$url.'?'.$params);
                curl_setopt ($curl_session, CURLOPT_RETURNTRANSFER, 1);

                if ($submit_method == 'post'){
                    curl_setopt ($curl_session, CURLOPT_POST, 1);
                } else {
                    curl_setopt($curl_session, CURLOPT_HTTPGET,  1);
                }
                
                curl_setopt ($curl_session, CURLOPT_FOLLOWLOCATION, 1);
                $return = curl_exec ($curl_session);
                curl_close ($curl_session);
                
                if ($record_log){
                    $this->recordLog("SMS Gateway return value: $return");
                }
                
                return $return;
            }
            else {
	   	if ($record_log){
               	    $this->recordLog("[[ UNABLE to send SMS at this time. ]]\r\n");
            	} 
	    } 
	    if ($record_log){
                $this->recordLog("[[ SMS function has been processed without success. ]]\r\n");
            } 
	    return false;

        }
    }

}

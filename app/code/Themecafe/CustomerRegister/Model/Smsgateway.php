<?php

/**
 * Copyright © 2015 Themecafe Design. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Themecafe\CustomerRegister\Model;

/**
 * Customer group model
 *
 * @method \Magento\Customer\Model\Resource\Group _getResource()
 * @method \Magento\Customer\Model\Resource\Group getResource()
 * @method string getCustomerGroupCode()
 * @method \Magento\Customer\Model\Group setCustomerGroupCode(string $value)
 * @method \Magento\Customer\Model\Group setTaxClassId(int $value)
 * @method Group setTaxClassName(string $value)
 */
class Smsgateway extends \Magento\Framework\Model\AbstractModel {

    protected $query_string;
    protected $_helper;
    
    public function __construct(
        \Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, 
        \Themecafe\CustomerRegister\Helper\Data $helper, 
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, 
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, 
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_helper = $helper;

    }
    
    protected function setQueryStringElement($query_str){
        $this->query_string = $query_str;
    }
    
    public function getQueryStringElement(){
        return $this->query_string;
    }
    
    public function sendSms($rcpt, $message, $customer_country_code, $storeId = null){
        return $this->_helper->sendSms($rcpt, $message, $customer_country_code, $storeId);
    }
}

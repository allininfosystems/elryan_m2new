<?php

namespace Themecafe\CustomerRegister\Logger;


class Handler extends \Magento\Framework\Logger\Handler\Base {
    
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;
 
    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/themecafe_customer_register.log';
}

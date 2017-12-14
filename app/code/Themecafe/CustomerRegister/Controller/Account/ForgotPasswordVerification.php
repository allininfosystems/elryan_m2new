<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Themecafe\CustomerRegister\Controller\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Response\Http;
use Magento\Store\Model\StoreManagerInterface; 

class ForgotPasswordVerification extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        PageFactory $resultPageFactory,
        Http $http,
        StoreManagerInterface $storeManager,
        \Themecafe\CustomerRegister\Helper\Data $data
    ) {
        $messageManager = $context->getMessageManager();
        $baseUrl = $storeManager->getStore()->getBaseUrl();
        if(!$data->isActive()){
            $http->setRedirect($baseUrl.'cms/noroute/index/');
        }
        if(!$customerSession->getData('forgotpasswordId')) {
            $messageManager->addError(__('Your password reset OTP has expired.'));
            $http->setRedirect($baseUrl.'customer/account/forgotpassword/');
        }
        $this->session = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Forgot customer password page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}

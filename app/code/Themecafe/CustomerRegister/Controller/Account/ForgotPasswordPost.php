<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Themecafe\CustomerRegister\Controller\Account;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Customer\Helper\View as CustomerViewHelper;
use Magento\Framework\App\ObjectManager;

class ForgotPasswordPost extends \Magento\Customer\Controller\AbstractAccount
{
    /** @var AccountManagementInterface */
    protected $customerAccountManagement;

    /** @var Escaper */
    protected $escaper;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param Escaper $escaper
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        Escaper $escaper,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository,
        CustomerRegistry $customerRegistry,
        DataObjectProcessor $dataProcessor,
        CustomerViewHelper $customerViewHelper,
	\Themecafe\CustomerRegister\Helper\Data $helper
            
    ) {
        $this->customerRegistry = $customerRegistry;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->dataProcessor = $dataProcessor;
        $this->customerViewHelper = $customerViewHelper;
	$this->helper = $helper;
        $this->escaper = $escaper;
        
        parent::__construct($context);
    }

    /**
     * Forgot customer password action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $email = (string)$this->getRequest()->getPost('email');
        $om = ObjectManager::getInstance();
        $customerSession = $om->create('Magento\Customer\Model\Session');
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $customerSession->setData('forgotpasswordId', $email);
            $customerFactory = $om->get('Magento\Customer\Model\CustomerFactory');
            $customerObj = $customerFactory->create()->getCollection()
                        ->addAttributeToFilter('themecafe_mobile',$email);
            if($customerObj->count()){
                $data = $customerObj->getData();
                $email = $data[0]['email'];
            }
        }
        else{
            $customerSession->setData('forgotpasswordId', $email);
        }
        
        
        if ($email) {
            /*if (!\Zend_Validate::is($email, 'EmailAddress')) {
                $this->session->setForgottenEmail($email);
                $this->messageManager->addErrorMessage(__('Please provide correct the email address / mobile number.'));
                return $resultRedirect->setPath('customer/account/forgotpassword');
            }*/
            try {
                $this->customerAccountManagement->initiatePasswordReset(
                    $email,
                    AccountManagement::EMAIL_RESET
                );
            } catch (NoSuchEntityException $e) {
                // Do nothing, we don't want anyone to use this action to determine which email accounts are registered.
                $this->messageManager->addErrorMessage(__("We're sorry. We weren't able to identify you with the given information."));
                return $resultRedirect->setPath('customer/account/forgotpassword');
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('System is unable to send the password reset email.')
                );
                return $resultRedirect->setPath('customer/account/forgotpassword');
            }
//            $this->messageManager->addSuccessMessage($this->getSuccessMessage($email));
            return $resultRedirect->setPath('themecafe_otpverification/account/forgotpasswordverification');
        } else {
            $this->messageManager->addErrorMessage(__('Please enter your email / mobile number.'));
            return $resultRedirect->setPath('customer/account/forgotpassword');
        }
    }

    /**
     * Retrieve success message
     *
     * @param string $email
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage($email)
    {
        return __(
           'You will receive a link to reset your password on registered email ID and Mobile number, please check your email and SMS.',
            $this->escaper->escapeHtml($email)
        );
    }
}

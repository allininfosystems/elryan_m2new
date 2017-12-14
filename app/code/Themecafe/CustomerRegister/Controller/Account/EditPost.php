<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Themecafe\CustomerRegister\Controller\Account;

use Magento\Customer\Model\Customer\Mapper;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Exception\InputException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;


/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class EditPost extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * Form code for data extractor
     */
    const FORM_DATA_EXTRACTOR_CODE = 'customer_account_edit';

    /** @var AccountManagementInterface */
    protected $customerAccountManagement;

    /** @var CustomerRepositoryInterface  */
    protected $customerRepository;

    /** @var Validator */
    protected $formKeyValidator;

    /** @var CustomerExtractor */
    protected $customerExtractor;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Mapper
     */
    private $customerMapper;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param CustomerRepositoryInterface $customerRepository
     * @param Validator $formKeyValidator
     * @param CustomerExtractor $customerExtractor
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        CustomerRepositoryInterface $customerRepository,
        Validator $formKeyValidator,
        CustomerExtractor $customerExtractor,
        StoreManagerInterface $storeManagerInterface,
        CustomerFactory $customerFactory,
	\Themecafe\CustomerRegister\Helper\Data $helper
    ) {
        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->customerRepository = $customerRepository;
        $this->formKeyValidator = $formKeyValidator;
        $this->customerExtractor = $customerExtractor;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->customerFactory = $customerFactory;
	$this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Change customer password action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setPath('customer/account/edit');
        }

        if ($this->getRequest()->isPost()) {
            $currentCustomerDataObject = $this->getCustomerDataObject($this->session->getCustomerId());
            $customerCandidateDataObject = $this->populateNewCustomerDataObject(
                $this->_request,
                $currentCustomerDataObject
            );
            
//            if($currentCustomerDataObject->getCustomAttribute('themecafe_mobile')){
               
//            }
            // Change customer password
            if ($this->getRequest()->getParam('change_password')) {
                $this->changeCustomerPassword($currentCustomerDataObject->getEmail());
            }
            if($this->helper->isActive()){
                //get customer already registered with same mobile number
                $websiteid = $this->storeManagerInterface->getStore()->getWebsiteId();
                $customerObj = $this->customerFactory->create()->getCollection()
                            ->addAttributeToFilter('themecafe_mobile',trim($this->getRequest()->getParam('themecafe_mobile')))
                            ->addFieldToFilter('entity_id',array('neq'=>$this->session->getCustomerId()));

                //check customer is sharing with multiple website or not
                if ($this->helper->getConfig('customer/account_share/scope')) {
                    $customerObj->addFieldToFilter('website_id', $websiteid);
                }
            }
            try {
                if($this->helper->isActive()){
                    /*customized for mobile number verification*/
			$customerMobile = "";
		    if($currentCustomerDataObject->getCustomAttribute('themecafe_mobile')){
                    $customerMobile = $currentCustomerDataObject->getCustomAttribute('themecafe_mobile')->getValue();
  	            }
                    $NewMobile = $this->getRequest()->getParam('themecafe_mobile');
                    if($NewMobile != $customerMobile){
                        if (!ctype_digit($NewMobile)) {
                            $message = __('Character(s) should not be allowed in mobile number, You have entered '.$NewMobile.'.');
                            throw new AuthenticationException($message);
                        }
                    /**
                     * Customized for enable/disable only OTP verification
                     * 
                     *  if((!$this->session->getData('mobile_verified') || $NewMobile != $this->session->getData('themecafeMobilenumber')) && $this->helper->getConfig('themecafe_customer_verification/general/active_otp')) {
                     */
                        if(!$this->session->getData('mobile_verified') || $NewMobile != $this->session->getData('themecafeMobilenumber')) {
                            $message = __('Mobile Number is not verified '.$NewMobile.'.');
                            throw new AuthenticationException($message);
                        }
                    }
                    //check if customer is already registered with same mobile number
                    if ($customerObj->count()){
                        $message = __('There is already an account with '.trim($this->getRequest()->getParam('themecafe_mobile')).' mobile number.');
                        throw new AuthenticationException($message);
                    }
                }
                $this->customerRepository->save($customerCandidateDataObject);
            } catch (AuthenticationException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (InputException $e) {
                $this->messageManager->addException($e, __('Invalid input'));
            } catch (\Exception $e) {
                $message = __('We can\'t save the customer.')
                    . $e->getMessage()
                    . '<pre>' . $e->getTraceAsString() . '</pre>';
                $this->messageManager->addException($e, $message);
            }

            if ($this->messageManager->getMessages()->getCount() > 0) {
                $this->session->setCustomerFormData($this->getRequest()->getPostValue());
                return $resultRedirect->setPath('customer/account/edit');
            }

            $this->messageManager->addSuccess(__('You saved the account information.'));
            return $resultRedirect->setPath('customer/account');
        }

        return $resultRedirect->setPath('customer/account/edit');
    }

    /**
     * Get customer data object
     *
     * @param int $customerId
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    private function getCustomerDataObject($customerId)
    {
        return $this->customerRepository->getById($customerId);
    }

    /**
     * Create Data Transfer Object of customer candidate
     *
     * @param \Magento\Framework\App\RequestInterface $inputData
     * @param \Magento\Customer\Api\Data\CustomerInterface $currentCustomerData
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    private function populateNewCustomerDataObject(
        \Magento\Framework\App\RequestInterface $inputData,
        \Magento\Customer\Api\Data\CustomerInterface $currentCustomerData
    ) {
        $attributeValues = $this->getCustomerMapper()->toFlatArray($currentCustomerData);
        $customerDto = $this->customerExtractor->extract(
            self::FORM_DATA_EXTRACTOR_CODE,
            $inputData,
            $attributeValues
        );
        $customerDto->setId($currentCustomerData->getId());
        if (!$customerDto->getAddresses()) {
            $customerDto->setAddresses($currentCustomerData->getAddresses());
        }

        return $customerDto;
    }

    /**
     * Change customer password
     *
     * @param string $email
     * @return $this
     */
    protected function changeCustomerPassword($email)
    {
        $currPass = $this->getRequest()->getPost('current_password');
        $newPass = $this->getRequest()->getPost('password');
        $confPass = $this->getRequest()->getPost('password_confirmation');

        if (!strlen($newPass)) {
            $this->messageManager->addError(__('Please enter new password.'));
            return $this;
        }

        if ($newPass !== $confPass) {
            $this->messageManager->addError(__('Confirm your new password.'));
            return $this;
        }

        try {
            $this->customerAccountManagement->changePassword($email, $currPass, $newPass);
        } catch (AuthenticationException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while changing the password.'));
        }

        return $this;
    }

    /**
     * Get Customer Mapper instance
     *
     * @return Mapper
     *
     * @deprecated
     */
    private function getCustomerMapper()
    {
        if ($this->customerMapper === null) {
            $this->customerMapper = ObjectManager::getInstance()->get(Mapper::class);
        }
        return $this->customerMapper;
    }
}

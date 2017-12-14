<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Themecafe\CustomerRegister\Controller\Adminhtml\Actions;

use Magento\Backend\App\Action;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CommentsHistory
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TestSending extends \Magento\Sales\Controller\Adminhtml\Order
{
    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;
    protected $helper;
    protected $_storeInterface;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Translate\InlineInterface $translateInline
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param OrderManagementInterface $orderManagement
     * @param OrderRepositoryInterface $orderRepository
     * @param LoggerInterface $logger
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Themecafe\CustomerRegister\Helper\Data $helper,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        OrderManagementInterface $orderManagement,
        OrderRepositoryInterface $orderRepository,
        LoggerInterface $logger,
        \Magento\Store\Model\StoreManagerInterface $storeInterface,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        $this->layoutFactory = $layoutFactory;
        parent::__construct(
            $context,
            $coreRegistry,
            $fileFactory,
            $translateInline,
            $resultPageFactory,
            $resultJsonFactory,
            $resultLayoutFactory,
            $resultRawFactory,
            $orderManagement,
            $orderRepository,
            $logger
        );
        $this->helper = $helper;
        $this->_storeInterface = $storeInterface;
    }

    
    public function execute()
    {
        try {
            $storeId = $this->_storeInterface->getStore()->getId();
            $mobile = $this->helper->originSender($storeId);
            $message = __('TEST MESSAGE FROM Themecafe COD Order Verification Module');
            $return_value = $this->helper->sendSms($mobile, $message, 'ALL', $storeId);
            $query_return = ($this->helper->getQueryStringElement()) ? $this->helper->getQueryStringElement() : __('No - please check your mobile number format and try again.');
            $query_values = __('Query sent to the server: %1. ', $query_return);
            $this->messageManager->addSuccess(__('Your test has been successfully sent. %1', $return_value).'<br />'.$query_values);
        } catch (\RuntimeException $e) {
            $this->messageManager->addError(__('Problem while sending your requestion. %1', $e->getMessage()));
        }
        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
        
    }
    
    protected function _isAllowed()
    {
        return true;
    }
}

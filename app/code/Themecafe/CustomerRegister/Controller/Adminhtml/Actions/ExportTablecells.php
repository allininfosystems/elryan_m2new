<?php
/**
 *
 * Copyright © 2015 Themecafe Design. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Themecafe\CustomerRegister\Controller\Adminhtml\Actions;

use Magento\Framework\App\ResponseInterface;
use Magento\Config\Controller\Adminhtml\System\ConfigSectionChecker;
use Magento\Framework\App\Filesystem\DirectoryList;

class ExportTablecells extends \Magento\Config\Controller\Adminhtml\System\AbstractConfig
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Config\Model\Config\Structure $configStructure
     * @param \Magento\Config\Controller\Adminhtml\System\ConfigSectionChecker $sectionChecker
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Config\Model\Config\Structure $configStructure,
        ConfigSectionChecker $sectionChecker,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        $this->_fileFactory = $fileFactory;
        parent::__construct($context, $configStructure, $sectionChecker);
    }

    /**
     * Export shipping table rates in csv format
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        $fileName = 'export.csv';
        /** @var $gridBlock \Themecafe\CustomerRegister\Block\Adminhtml\Cell\Tablecell\Grid */
        $gridBlock = $this->_view->getLayout()->createBlock(
            'Themecafe\CustomerRegister\Block\Adminhtml\Cell\Tablecell\Grid'
        );
        $website = $this->_storeManager->getWebsite($this->getRequest()->getParam('website'));
        $gridBlock->setWebsiteId($website->getId());
        $content = $gridBlock->getCsvFile();
        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}

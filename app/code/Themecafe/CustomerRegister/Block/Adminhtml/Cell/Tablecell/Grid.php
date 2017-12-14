<?php
/**
 * Copyright © 2015 Themecafe Design. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Themecafe\CustomerRegister\Block\Adminhtml\Cell\Tablecell;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Website filter
     *
     * @var int
     */
    protected $_websiteId;

    protected $_tablecell;

   
    protected $_collectionFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Themecafe\CustomerRegister\Model\ResourceModel\Cell\Tablecell\CollectionFactory $collectionFactory,
        \Themecafe\CustomerRegister\Model\Tablecell $tablecell,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_tablecell = $tablecell;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Define grid properties
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('shippingTablecellGrid');
        $this->_exportPageSize = 10000;
    }

    /**
     * Set current website
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId($websiteId)
    {
        $this->_websiteId = $this->_storeManager->getWebsite($websiteId)->getId();
        return $this;
    }

    /**
     * Retrieve current website id
     *
     * @return int
     */
    public function getWebsiteId()
    {
        if ($this->_websiteId === null) {
            $this->_websiteId = $this->_storeManager->getWebsite()->getId();
        }
        return $this->_websiteId;
    }

    
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare table columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'dest_country',
            ['header' => __('Country'), 'index' => 'dest_country', 'default' => '*']
        );

        $this->addColumn(
            'international_code',
            ['header' => __('International Code'), 'index' => 'international_code', 'default' => '*']
        );

        $this->addColumn(
            'mobile_code',
            ['header' => __('Mobile Code'), 'index' => 'mobile_code', 'default' => '*']
        );
        
        $this->addColumn(
            'regex_mask',
            ['header' => __('Regex Mask'), 'index' => 'regex_mask', 'default' => '*']
        );

        return parent::_prepareColumns();
    }
}

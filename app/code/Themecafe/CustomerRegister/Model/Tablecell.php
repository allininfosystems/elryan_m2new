<?php

/**
 * Copyright © 2015 Themecafe Design. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Themecafe\CustomerRegister\Model;


class Tablecell extends \Magento\Framework\App\Config\Value 
{

    protected $query_string;
    protected $_helper;
    protected $_tablecellFactory;
    
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Themecafe\CustomerRegister\Model\ResourceModel\Cell\TablecellFactory $tablecellFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_tablecellFactory = $tablecellFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }
    
    public function afterSave()
    {
        $tableCell = $this->_tablecellFactory->create();
        $tableCell->uploadAndImport($this);
        return parent::afterSave();
    }
    /*
    public function afterSave() {
        $this->updateFlatRecords();
        return parent::afterSave();
    }

    public function afterDelete() {
        $this->updateFlatRecords();
        return parent::afterDelete();
    }
    */
}

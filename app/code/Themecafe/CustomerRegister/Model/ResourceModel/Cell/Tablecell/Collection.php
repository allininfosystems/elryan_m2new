<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Themecafe\CustomerRegister\Model\ResourceModel\Cell\Tablecell;

/**
 * Shipping table rates collection
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Directory/country table name
     *
     * @var string
     */
    protected $_countryTable;

    /**
     * Define resource model and item
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Themecafe\CustomerRegister\Model\Tablecell',
            'Themecafe\CustomerRegister\Model\ResourceModel\Cell\Tablecell'
        );
        $this->_countryTable = $this->getTable('directory_country');
    }

    
    public function _initSelect()
    {
        parent::_initSelect();

        $this->_select->joinLeft(
            ['country_table' => $this->_countryTable],
            'country_table.country_id = main_table.dest_country_id',
            ['dest_country' => 'iso3_code']
        );

        $this->addOrder('dest_country', self::SORT_ORDER_ASC);
    }

    
    
    public function setCountryFilter($countryId)
    {
        if ($countryId != "ALL")
            return $this->addFieldToFilter('dest_country_id', $countryId);
        return $this;
    }
}

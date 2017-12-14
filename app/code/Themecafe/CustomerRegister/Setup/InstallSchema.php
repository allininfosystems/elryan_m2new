<?php
/**
 * Copyright © 2015 Themecafe DESIGN. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Themecafe\CustomerRegister\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        /**
        * Create table 'themecafe_otp_code'
        */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('themecafe_otp_code')
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity Id'
            )->addColumn(
                'international_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                4,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'International Code'
            )->addColumn(
                'dest_country_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                4,
                [],
                'Destination Country'
            )->addColumn(
                'mobile_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                11,
                [],
                'Mobile Code'
            )->addColumn(
                'regex_mask',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => null],
                'Regex Mask'
            )->addIndex(
                $installer->getIdxName('themecafe_number_code', ['international_code', 'dest_country_id', 'mobile_code']),
                ['international_code', 'dest_country_id', 'mobile_code']
            )->setComment(
                'Themecafe OTP verification on Cash On Delivery '
        );
           
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}

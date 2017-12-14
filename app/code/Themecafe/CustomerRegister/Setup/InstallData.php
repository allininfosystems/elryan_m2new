<?php
/**
 * Copyright © 2015 Themecafe DESIGN. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Themecafe\CustomerRegister\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(Customer::ENTITY, 'themecafe_mobile', [
            'type' => 'varchar',
            'label' => 'Mobile Number',
            'input' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 1000,
            'position' => 1000,
            'system' => 0,
            'unique' => true,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => true,
            'is_filterable_in_grid' => true,
            'is_searchable_in_grid' => true,
        ]);
        $customerSetup->addAttribute(Customer::ENTITY, 'themecafe_mobile_verify', [
            'type' => 'int',
            'label' => 'Mobile Number Verified?',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'input' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            'required' => false,
            'visible' => true,
            'user_defined' => false,
            'sort_order' => 1000,
            'position' => 1000,
            'system' => 0,
            'default' => 0
        ]);
        //add attribute to attribute set
        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'themecafe_mobile')
        ->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer','customer_account_create', 'customer_account_edit'],
        ]);
        $attribute->save();
        $attribute2 = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'themecafe_mobile_verify')
        ->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer','customer_account_create','customer_account_edit'],
        ]);
        $attribute2->save();
    }
}

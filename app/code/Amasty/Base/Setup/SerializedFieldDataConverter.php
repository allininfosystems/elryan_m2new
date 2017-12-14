<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Setup;

use Magento\Framework\DB\AggregatedFieldDataConverter;

class SerializedFieldDataConverter
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $dataSetup;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Setup\ModuleDataSetupInterface $dataSetup
    ) {
        $this->objectManager = $objectManager;
        $this->dataSetup = $dataSetup;
    }

    /**
     * Convert metadata from serialized to JSON format:
     *
     * @param $tableName
     * @param $identifierField
     * @param $fields
     * @return void
     */
    public function convertSerializedDataToJson($tableName, $identifierField, $fields)
    {
        /** @var AggregatedFieldDataConverter $aggregatedFieldConverter */
        $fieldConverter = $this->objectManager->get(AggregatedFieldDataConverter::class);
        $convertData = [];
        foreach ($fields as $field) {
            $convertData[] = new \Magento\Framework\DB\FieldToConvert(
                \Magento\Framework\DB\DataConverter\SerializedToJson::class,
                $this->dataSetup->getTable($tableName),
                $identifierField,
                $field
            );
        }

        $fieldConverter->convert(
            $convertData,
            $this->dataSetup->getConnection()
        );
    }
}

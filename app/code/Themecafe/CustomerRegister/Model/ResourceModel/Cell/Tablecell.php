<?php
/**
 * Copyright © 2015 Themecafe Design. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Themecafe\CustomerRegister\Model\ResourceModel\Cell;

use Magento\Framework\Filesystem\DirectoryList;

class Tablecell extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Import table rates website ID
     *
     * @var int
     */
    protected $_importWebsiteId = 0;

    /**
     * Errors in import process
     *
     * @var array
     */
    protected $_importErrors = [];

    /**
     * Count of imported table rates
     *
     * @var int
     */
    protected $_importedRows = 0;

    /**
     * Array of unique table rate keys to protect from duplicates
     *
     * @var array
     */
    protected $_importUniqueHash = [];

    /**
     * Array of countries keyed by iso2 code
     *
     * @var array
     */
    protected $_importIso2Countries;

    /**
     * Array of countries keyed by iso3 code
     *
     * @var array
     */
    protected $_importIso3Countries;

    /**
     * Associative array of countries and regions
     * [country_id][region_code] = region_id
     *
     * @var array
     */
    protected $_importRegions;

    /**
     * Import Table Rate condition name
     *
     * @var string
     */
    protected $_importConditionName;

    /**
     * Array of condition full names
     *
     * @var array
     */
    protected $_conditionFullNames = [];

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_coreConfig;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    
    protected $_carrierTablerate;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Country\CollectionFactory
     */
    protected $_countryCollectionFactory;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Region\CollectionFactory
     */
    protected $_regionCollectionFactory;

    /**
     * Filesystem instance
     *
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;
    
    protected $_fileUploaderFactory;
    
    protected $_requestData;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $coreConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\OfflineShipping\Model\Carrier\Tablerate $carrierTablerate
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     * @param \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory
     * @param \Magento\Framework\Filesystem $filesystem
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $coreConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\OfflineShipping\Model\Carrier\Tablerate $carrierTablerate,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Config\Model\Config\Backend\File\RequestData\RequestDataInterface $requestData,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_coreConfig = $coreConfig;
        $this->_logger = $logger;
        $this->_storeManager = $storeManager;
        $this->_carrierTablerate = $carrierTablerate;
        $this->_countryCollectionFactory = $countryCollectionFactory;
        $this->_regionCollectionFactory = $regionCollectionFactory;
        $this->_filesystem = $filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_requestData = $requestData;
    }

    /**
     * Define main table and id field name
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('themecafe_otp_code', 'entity_id');
    }

    
    public function uploadAndImport(\Magento\Framework\DataObject $object)
    {
        $value = $object->getValue();
        $tmpName = $this->_requestData->getTmpName($object->getPath());
        $file = [];
        if ($tmpName) {
            $file['tmp_name'] = $tmpName;
            $file['name'] = $this->_requestData->getName($object->getPath());
        } elseif (!empty($value['tmp_name'])) {
            $file['tmp_name'] = $value['tmp_name'];
            $file['name'] = $value['value'];
        }
        
        if (empty($file)){
            return $this;
        }
        
        $uploader = $this->_fileUploaderFactory->create(['fileId' => $file]);
        
        $fileUploader = $uploader->validateFile();
        if (empty($fileUploader)){
            return $this;
        }
        $csvFile = $fileUploader['tmp_name'];
        
        $website = $this->_storeManager->getWebsite($object->getScopeId());
        
        $this->_importWebsiteId = (int)$website->getId();
        $this->_importUniqueHash = [];
        $this->_importErrors = [];
        $this->_importedRows = 0;

        $tmpDirectory = $this->_filesystem->getDirectoryRead(DirectoryList::SYS_TMP);
        $path = $tmpDirectory->getRelativePath($csvFile);
        
        $stream = $tmpDirectory->openFile($path);

        // check and skip headers
        $headers = $stream->readCsv();
        if ($headers === false || count($headers) < 3) {
            $stream->close();
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid File Format.'));
        }

        $connection = $this->getConnection();
        $connection->beginTransaction();

        try {
            $rowNumber = 1;
            $importData = [];

            $this->_loadDirectoryCountries();
            $connection->delete($this->getMainTable());

            while (false !== ($csvLine = $stream->readCsv())) {
                $rowNumber++;

                if (empty($csvLine)) {
                    continue;
                }

                $row = $this->_getImportRow($csvLine, $rowNumber);
                if ($row !== false) {
                    $importData[] = $row;
                }

                if (count($importData) == 5000) {
                    $this->_saveImportData($importData);
                    $importData = [];
                }
            }
            $this->_saveImportData($importData);
            $stream->close();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $connection->rollback();
            $stream->close();
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
        } catch (\Exception $e) {
            $connection->rollback();
            $stream->close();
            $this->_logger->critical($e);
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while importing table rates.')
            );
        }

        $connection->commit();

        if ($this->_importErrors) {
            $error = __(
                'We couldn\'t import this file because of these errors: %1',
                implode(" \n", $this->_importErrors)
            );
            throw new \Magento\Framework\Exception\LocalizedException($error);
        }

        return $this;
    }
   
    protected function _loadDirectoryCountries()
    {
        if ($this->_importIso2Countries !== null && $this->_importIso3Countries !== null) {
            return $this;
        }

        $this->_importIso2Countries = [];
        $this->_importIso3Countries = [];

        /** @var $collection \Magento\Directory\Model\ResourceModel\Country\Collection */
        $collection = $this->_countryCollectionFactory->create();
        foreach ($collection->getData() as $row) {
            $this->_importIso2Countries[$row['iso2_code']] = $row['country_id'];
            $this->_importIso3Countries[$row['iso3_code']] = $row['country_id'];
        }

        return $this;
    }
    
    protected function _loadDirectoryRegions()
    {
        if ($this->_importRegions !== null) {
            return $this;
        }

        $this->_importRegions = [];

        /** @var $collection \Magento\Directory\Model\ResourceModel\Region\Collection */
        $collection = $this->_regionCollectionFactory->create();
        foreach ($collection->getData() as $row) {
            $this->_importRegions[$row['country_id']][$row['code']] = (int)$row['region_id'];
        }

        return $this;
    }

    
    protected function _getImportRow($row, $rowNumber = 0)
    {
        // validate row
        if (count($row) < 3) {
            $this->_importErrors[] = __('Please correct Table Cells format in the Row #%1.', $rowNumber);
            return false;
        }

        // strip whitespace from the beginning and end of each row
        foreach ($row as $k => $v) {
            $row[$k] = trim($v);
        }

        // validate country
        if (isset($this->_importIso2Countries[$row[0]])) {
            $countryId = $this->_importIso2Countries[$row[0]];
        } elseif (isset($this->_importIso3Countries[$row[0]])) {
            $countryId = $this->_importIso3Countries[$row[0]];
        } elseif ($row[0] == '*' || $row[0] == '') {
            $countryId = '0';
        } else {
            $this->_importErrors[] = __('Please correct Country "%1" in the Row #%2.', $row[0], $rowNumber);
            return false;
        }

        if ($row[1] == '*' || $row[1] == '') {
            $this->_importErrors[] = __('Invalid international number "%s" in the Row #%s', $row[1], $rowNumber);
        } 

        if ($row[2] == '*' || $row[2] == '') {
            $this->_importErrors[] = __('Invalid mobile code "%s" in the Row #%s', $row[2], $rowNumber);
        }
        
        if ($row[3] == '*' || $row[3] == ''){
            //regex_mask
            $this->_importErrors[] = __('Invalid regex mask "%s" in the Row #%s', $csvLine[3], $rowNumber);
        }
        
        return [
            $row[1],    // international_code
            $countryId,                 // dest_country_id
            $row[2],                // mobile_code,
            $row[3]                // regex_mask
        ];
    }

    protected function _saveImportData(array $data)
    {
        if (!empty($data)) {
            $columns = [
                'international_code',
                'dest_country_id',
                'mobile_code',
                'regex_mask',
            ];
            $this->getConnection()->insertArray($this->getMainTable(), $columns, $data);
            $this->_importedRows += count($data);
        }

        return $this;
    }

    
    protected function _parseDecimalValue($value)
    {
        if (!is_numeric($value)) {
            return false;
        }
        $value = (double)sprintf('%.4F', $value);
        if ($value < 0.0000) {
            return false;
        }
        return $value;
    }
}

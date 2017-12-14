<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Number
 */


namespace Amasty\Number\Model\Config\Backend;

use Magento\Framework\Exception\LocalizedException;

class Number extends \Magento\Framework\App\Config\Value
{

    const CONFIG_PATH_METHOD_NAME_POSITION_KEY = 1;

    /**
     * @var array
     */
    protected $requirementFields = ['counter', 'rand', 'order_id'];

    /**
     * @var \Amasty\Number\Helper\Data
     */
    protected $helperData;

    /**
     * Number constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Amasty\Number\Helper\Data $helperData
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Amasty\Number\Helper\Data $helperData,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->helperData = $helperData;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        try {
            $out = [];
            $msg = '';
            $methodName = $this->getPathMethodName();
            $lastIncrementOrderId = $this->helperData->getLastIncrementId($methodName);
            $value = $this->helperData->getFormatIncrementId(
                $methodName,
                $this->getScopeId(),
                $lastIncrementOrderId,
                0,
                $this->getValue()
            );

            preg_match_all('|{(.*)}|Uis', $this->getValue(), $out);

            if (!$out || !$this->_validateLength($value)) {
                $msg = __(
                    sprintf(
                        'Number length for %s must be less or equals than 32 symbols or not contain only alphanumeric. 
                Last increment ID is: %s. Please change pattern. Supposed Increment ID: %s',
                        $methodName,
                        $lastIncrementOrderId,
                        $value
                    )
                );
            } elseif (array_key_exists(1, $out) && !array_intersect($this->requirementFields, $out[1])) {
                $msg = __(
                    sprintf(
                        'Number format for %s need contain one from this fields: '
                        . implode(', ', $this->requirementFields) . '
                Last increment ID is: %s. Please change pattern. Supposed Increment ID: %s',
                        $methodName,
                        $lastIncrementOrderId,
                        $value
                    )
                );
            }

            if ($msg) {
                throw new LocalizedException($msg);
            }

        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            throw new LocalizedException(__($e->getMessage()), $e);
        }
    }

    /**
     * @param $number
     * @return bool
     */
    protected function _validateLength($number)
    {
        $passed  = (strlen($number) > 32) ? false : true;

        return $passed;
    }

    /**
     * @return string
     */
    protected function getPathMethodName()
    {
        $pathArray = explode('/', $this->getPath());

        return array_key_exists(self::CONFIG_PATH_METHOD_NAME_POSITION_KEY, $pathArray)
            ? $pathArray[self::CONFIG_PATH_METHOD_NAME_POSITION_KEY] : '';
    }
}

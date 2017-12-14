<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Number
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Number\Plugin;

class EntityType
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Amasty\Number\Helper\Data
     */
    protected $helper;

    /**
     * @var string
     */
    protected $type = 'order';

    /**
     * EntityType constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Amasty\Number\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Amasty\Number\Helper\Data $helper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->objectManager = $objectManager;
        $this->_storeManager = $storeManager;
        $this->helper = $helper;
    }

    /**
     * Retreive new incrementId
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Closure $closure
     * @return string
     * @internal param int $storeId
     */
    public function aroundReserveOrderId(
        \Magento\Quote\Model\Quote $quote,
        \Closure $closure
    ) {
        $result = false;
        $storeId = $quote->getStoreId();
        $incrementId = $quote->getReservedOrderId();

        if (!$incrementId) {
            $result = $closure();
            $incrementId = $result->getReservedOrderId();
        }

        if (!$this->helper->getConfigValueByPath('amnumber/general/enabled', $storeId)
            || $this->helper->getConfigValueByPath('amnumber/'. $this->type .'/same', $storeId)) {
            return $closure();
        }

        $incrementId = $this->helper->getFormatIncrementId($this->type, $storeId, $incrementId);
        $quote->setReservedOrderId($incrementId);
        $this->helper->flushConfigCache();

        return !$result ? $closure() : $result;
    }
}

<?php
namespace Amasty\Shopby\Helper\FilterSetting;

/**
 * Proxy class for @see \Amasty\Shopby\Helper\FilterSetting
 */
class Proxy extends \Amasty\Shopby\Helper\FilterSetting implements \Magento\Framework\ObjectManager\NoninterceptableInterface
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Proxied instance name
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Proxied instance
     *
     * @var \Amasty\Shopby\Helper\FilterSetting
     */
    protected $_subject = null;

    /**
     * Instance shareability flag
     *
     * @var bool
     */
    protected $_isShared = null;

    /**
     * Proxy constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     * @param bool $shared
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Amasty\\Shopby\\Helper\\FilterSetting', $shared = true)
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
        $this->_isShared = $shared;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['_subject', '_isShared', '_instanceName'];
    }

    /**
     * Retrieve ObjectManager from global scope
     */
    public function __wakeup()
    {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     * Clone proxied instance
     */
    public function __clone()
    {
        $this->_subject = clone $this->_getSubject();
    }

    /**
     * Get proxied instance
     *
     * @return \Amasty\Shopby\Helper\FilterSetting
     */
    protected function _getSubject()
    {
        if (!$this->_subject) {
            $this->_subject = true === $this->_isShared
                ? $this->_objectManager->get($this->_instanceName)
                : $this->_objectManager->create($this->_instanceName);
        }
        return $this->_subject;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingByLayerFilter(\Magento\Catalog\Model\Layer\Filter\FilterInterface $layerFilter)
    {
        return $this->_getSubject()->getSettingByLayerFilter($layerFilter);
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingByAttribute($attributeModel)
    {
        return $this->_getSubject()->getSettingByAttribute($attributeModel);
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingByAttributeCode($attributeCode)
    {
        return $this->_getSubject()->getSettingByAttributeCode($attributeCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomDataForCategoryFilter()
    {
        return $this->_getSubject()->getCustomDataForCategoryFilter();
    }

    /**
     * {@inheritdoc}
     */
    public function getKeyValueForCategoryFilterConfig()
    {
        return $this->_getSubject()->getKeyValueForCategoryFilterConfig();
    }

    /**
     * {@inheritdoc}
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        return $this->_getSubject()->isModuleOutputEnabled($moduleName);
    }
}

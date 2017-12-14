<?php
namespace Amasty\Shopby\Model\Layer\Filter\Attribute;

/**
 * Interceptor class for @see \Amasty\Shopby\Model\Layer\Filter\Attribute
 */
class Interceptor extends \Amasty\Shopby\Model\Layer\Filter\Attribute implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Model\Layer $layer, \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder, \Magento\Framework\Filter\StripTags $tagFilter, \Amasty\Shopby\Model\Search\Adapter\Mysql\AggregationAdapter $aggregationAdapter, \Magento\Search\Model\SearchEngine $searchEngine, \Amasty\Shopby\Helper\FilterSetting $settingHelper, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Amasty\Shopby\Model\Request $shopbyRequest, \Amasty\Shopby\Helper\Group $groupHelper, \Amasty\Shopby\Helper\OptionSetting $optionSettingHelper, array $data = array())
    {
        $this->___init();
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $tagFilter, $aggregationAdapter, $searchEngine, $settingHelper, $scopeConfig, $shopbyRequest, $groupHelper, $optionSettingHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'apply');
        if (!$pluginInfo) {
            return parent::apply($request);
        } else {
            return $this->___callPlugins('apply', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function shouldAddState()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'shouldAddState');
        if (!$pluginInfo) {
            return parent::shouldAddState();
        } else {
            return $this->___callPlugins('shouldAddState', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsCount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemsCount');
        if (!$pluginInfo) {
            return parent::getItemsCount();
        } else {
            return $this->___callPlugins('getItemsCount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sortOption($a, $b)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'sortOption');
        if (!$pluginInfo) {
            return parent::sortOption($a, $b);
        } else {
            return $this->___callPlugins('sortOption', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isVisibleWhenSelected()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isVisibleWhenSelected');
        if (!$pluginInfo) {
            return parent::isVisibleWhenSelected();
        } else {
            return $this->___callPlugins('isVisibleWhenSelected', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sortOptionsByFeatured(&$options)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'sortOptionsByFeatured');
        if (!$pluginInfo) {
            return parent::sortOptionsByFeatured($options);
        } else {
            return $this->___callPlugins('sortOptionsByFeatured', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRequestVar($varName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRequestVar');
        if (!$pluginInfo) {
            return parent::setRequestVar($varName);
        } else {
            return $this->___callPlugins('setRequestVar', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestVar()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRequestVar');
        if (!$pluginInfo) {
            return parent::getRequestVar();
        } else {
            return $this->___callPlugins('getRequestVar', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResetValue()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResetValue');
        if (!$pluginInfo) {
            return parent::getResetValue();
        } else {
            return $this->___callPlugins('getResetValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCleanValue()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCleanValue');
        if (!$pluginInfo) {
            return parent::getCleanValue();
        } else {
            return $this->___callPlugins('getCleanValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItems');
        if (!$pluginInfo) {
            return parent::getItems();
        } else {
            return $this->___callPlugins('getItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setItems(array $items)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setItems');
        if (!$pluginInfo) {
            return parent::setItems($items);
        } else {
            return $this->___callPlugins('setItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLayer()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLayer');
        if (!$pluginInfo) {
            return parent::getLayer();
        } else {
            return $this->___callPlugins('getLayer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributeModel($attribute)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAttributeModel');
        if (!$pluginInfo) {
            return parent::setAttributeModel($attribute);
        } else {
            return $this->___callPlugins('setAttributeModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeModel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttributeModel');
        if (!$pluginInfo) {
            return parent::getAttributeModel();
        } else {
            return $this->___callPlugins('getAttributeModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getName');
        if (!$pluginInfo) {
            return parent::getName();
        } else {
            return $this->___callPlugins('getName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoreId');
        if (!$pluginInfo) {
            return parent::getStoreId();
        } else {
            return $this->___callPlugins('getStoreId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($storeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStoreId');
        if (!$pluginInfo) {
            return parent::setStoreId($storeId);
        } else {
            return $this->___callPlugins('setStoreId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWebsiteId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWebsiteId');
        if (!$pluginInfo) {
            return parent::getWebsiteId();
        } else {
            return $this->___callPlugins('getWebsiteId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWebsiteId($websiteId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWebsiteId');
        if (!$pluginInfo) {
            return parent::setWebsiteId($websiteId);
        } else {
            return $this->___callPlugins('setWebsiteId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getClearLinkText()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getClearLinkText');
        if (!$pluginInfo) {
            return parent::getClearLinkText();
        } else {
            return $this->___callPlugins('getClearLinkText', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addData(array $arr)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addData');
        if (!$pluginInfo) {
            return parent::addData($arr);
        } else {
            return $this->___callPlugins('addData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setData($key, $value = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setData');
        if (!$pluginInfo) {
            return parent::setData($key, $value);
        } else {
            return $this->___callPlugins('setData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsetData($key = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsetData');
        if (!$pluginInfo) {
            return parent::unsetData($key);
        } else {
            return $this->___callPlugins('unsetData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getData($key = '', $index = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getData');
        if (!$pluginInfo) {
            return parent::getData($key, $index);
        } else {
            return $this->___callPlugins('getData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByPath($path)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataByPath');
        if (!$pluginInfo) {
            return parent::getDataByPath($path);
        } else {
            return $this->___callPlugins('getDataByPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByKey($key)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataByKey');
        if (!$pluginInfo) {
            return parent::getDataByKey($key);
        } else {
            return $this->___callPlugins('getDataByKey', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDataUsingMethod($key, $args = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDataUsingMethod');
        if (!$pluginInfo) {
            return parent::setDataUsingMethod($key, $args);
        } else {
            return $this->___callPlugins('setDataUsingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataUsingMethod($key, $args = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataUsingMethod');
        if (!$pluginInfo) {
            return parent::getDataUsingMethod($key, $args);
        } else {
            return $this->___callPlugins('getDataUsingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasData($key = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasData');
        if (!$pluginInfo) {
            return parent::hasData($key);
        } else {
            return $this->___callPlugins('hasData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toArray');
        if (!$pluginInfo) {
            return parent::toArray($keys);
        } else {
            return $this->___callPlugins('toArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToArray(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToArray');
        if (!$pluginInfo) {
            return parent::convertToArray($keys);
        } else {
            return $this->___callPlugins('convertToArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toXml(array $keys = array(), $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toXml');
        if (!$pluginInfo) {
            return parent::toXml($keys, $rootName, $addOpenTag, $addCdata);
        } else {
            return $this->___callPlugins('toXml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToXml(array $arrAttributes = array(), $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToXml');
        if (!$pluginInfo) {
            return parent::convertToXml($arrAttributes, $rootName, $addOpenTag, $addCdata);
        } else {
            return $this->___callPlugins('convertToXml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toJson(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toJson');
        if (!$pluginInfo) {
            return parent::toJson($keys);
        } else {
            return $this->___callPlugins('toJson', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToJson(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToJson');
        if (!$pluginInfo) {
            return parent::convertToJson($keys);
        } else {
            return $this->___callPlugins('convertToJson', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toString($format = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toString');
        if (!$pluginInfo) {
            return parent::toString($format);
        } else {
            return $this->___callPlugins('toString', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $args)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '__call');
        if (!$pluginInfo) {
            return parent::__call($method, $args);
        } else {
            return $this->___callPlugins('__call', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isEmpty');
        if (!$pluginInfo) {
            return parent::isEmpty();
        } else {
            return $this->___callPlugins('isEmpty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($keys = array(), $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'serialize');
        if (!$pluginInfo) {
            return parent::serialize($keys, $valueSeparator, $fieldSeparator, $quote);
        } else {
            return $this->___callPlugins('serialize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function debug($data = null, &$objects = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'debug');
        if (!$pluginInfo) {
            return parent::debug($data, $objects);
        } else {
            return $this->___callPlugins('debug', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetSet');
        if (!$pluginInfo) {
            return parent::offsetSet($offset, $value);
        } else {
            return $this->___callPlugins('offsetSet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetExists');
        if (!$pluginInfo) {
            return parent::offsetExists($offset);
        } else {
            return $this->___callPlugins('offsetExists', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetUnset');
        if (!$pluginInfo) {
            return parent::offsetUnset($offset);
        } else {
            return $this->___callPlugins('offsetUnset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetGet');
        if (!$pluginInfo) {
            return parent::offsetGet($offset);
        } else {
            return $this->___callPlugins('offsetGet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isApplied()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isApplied');
        if (!$pluginInfo) {
            return parent::isApplied();
        } else {
            return $this->___callPlugins('isApplied', func_get_args(), $pluginInfo);
        }
    }
}

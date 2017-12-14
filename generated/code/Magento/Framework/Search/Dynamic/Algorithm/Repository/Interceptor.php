<?php
namespace Magento\Framework\Search\Dynamic\Algorithm\Repository;

/**
 * Interceptor class for @see \Magento\Framework\Search\Dynamic\Algorithm\Repository
 */
class Interceptor extends \Magento\Framework\Search\Dynamic\Algorithm\Repository implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, array $algorithms)
    {
        $this->___init();
        parent::__construct($objectManager, $algorithms);
    }

    /**
     * {@inheritdoc}
     */
    public function get($algorithmType, array $data = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'get');
        if (!$pluginInfo) {
            return parent::get($algorithmType, $data);
        } else {
            return $this->___callPlugins('get', func_get_args(), $pluginInfo);
        }
    }
}

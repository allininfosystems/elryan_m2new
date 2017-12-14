<?php
namespace Magento\Framework\Search\Adapter\Mysql\Aggregation\Builder\Dynamic;

/**
 * Interceptor class for @see \Magento\Framework\Search\Adapter\Mysql\Aggregation\Builder\Dynamic
 */
class Interceptor extends \Magento\Framework\Search\Adapter\Mysql\Aggregation\Builder\Dynamic implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Search\Dynamic\Algorithm\Repository $algorithmRepository, \Magento\Framework\Search\Dynamic\EntityStorageFactory $entityStorageFactory)
    {
        $this->___init();
        parent::__construct($algorithmRepository, $entityStorageFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function build(\Magento\Framework\Search\Adapter\Mysql\Aggregation\DataProviderInterface $dataProvider, array $dimensions, \Magento\Framework\Search\Request\BucketInterface $bucket, \Magento\Framework\DB\Ddl\Table $entityIdsTable)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'build');
        if (!$pluginInfo) {
            return parent::build($dataProvider, $dimensions, $bucket, $entityIdsTable);
        } else {
            return $this->___callPlugins('build', func_get_args(), $pluginInfo);
        }
    }
}

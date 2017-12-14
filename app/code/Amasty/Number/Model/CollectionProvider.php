<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Number
 */

/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Amasty\Number\Model;

use Magento\Catalog\Model\ProductLink\Converter\ConverterPool;
use Magento\Framework\Exception\NoSuchEntityException;

class CollectionProvider
{
    /**
     * @var CollectionProviderInterface[]|array
     */
    protected $providers;

    /**
     * CollectionProvider constructor.
     * @param array $providers
     */
    public function __construct(array $providers = [])
    {
        $this->providers = $providers;
    }

    /**
     * @param $type
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getCollection($type)
    {
        if (!isset($this->providers[$type])) {
            throw new NoSuchEntityException(__('Collection provider is not registered'));
        }

        return $this->providers[$type]->create();
    }
}

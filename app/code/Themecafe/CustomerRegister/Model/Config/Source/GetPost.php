<?php
/**
 * Copyright © 2015 Themecafe Design. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Themecafe\CustomerRegister\Model\Config\Source;

class GetPost implements \Magento\Framework\Option\ArrayInterface
{
    
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'get', 'label'=> __('Get')],
            ['value' => 'post', 'label'=> __('Post')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ['get' => __('Get'), 'post' => __('Post')];
    }
}

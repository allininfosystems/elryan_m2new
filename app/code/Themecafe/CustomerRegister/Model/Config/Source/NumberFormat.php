<?php
/**
 * Copyright © 2015 Themecafe Design. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Themecafe\CustomerRegister\Model\Config\Source;

class NumberFormat implements \Magento\Framework\Option\ArrayInterface
{
    
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label'=> __('International Number Without + Sign')],
            ['value' => '1', 'label'=> __('International Number With + Sign')],
            ['value' => '2', 'label'=> __('Only 10 Digit Number')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ['0' => __('International Number Without + Sign'), '1' => __('International Number With + Sign')];
    }
}

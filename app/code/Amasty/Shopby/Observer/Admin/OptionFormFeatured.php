<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Observer\Admin;

use Magento\Framework\Data\Form;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class OptionFormFeatured
 * @package Amasty\Shopby\Observer\Admin
 */
class OptionFormFeatured implements ObserverInterface
{
    /**
     * @var Yesno
     */
    private $yesNoSource;

    /**
     * OptionFormFeatured constructor.
     * @param Yesno $yesNoSource
     */
    public function __construct(
        Yesno $yesNoSource
    ) {
        $this->yesNoSource = $yesNoSource;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Form $form */
        $form = $observer->getData('form');

        $featuredFieldset = $form->addFieldset(
            'featured_option_fieldset',
            ['legend' => __('Featured'), 'class'=>'form-inline']
        );

        $featuredFieldset->addField(
            'is_featured',
            'select',
            [
                'name' => 'is_featured',
                'label' => __('Featured'),
                'title' => __('Featured'),
                'values' => $this->yesNoSource->toOptionArray()
            ]
        );
    }
}

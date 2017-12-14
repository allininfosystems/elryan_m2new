<?php
/**
 * Copyright © 2015 Themecafe Design. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Themecafe\CustomerRegister\Block\Adminhtml\Form\Field;


class Download extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    
    protected $_backendUrl;

    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        array $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->_backendUrl = $backendUrl;
    }

    public function getElementHtml()
    {
        $buttonBlock = $this->getForm()->getParent()->getLayout()->createBlock('Magento\Backend\Block\Widget\Button');
        $params = ['website' => $buttonBlock->getRequest()->getParam('website')];
        $url = $this->_backendUrl->getUrl("themecafe_customerregister/actions/downloadTablecells", $params);
        $data = [
            'label' => __('Download Sample'),
            'onclick' => "setLocation('".$url."')",
            'class' => '',
        ];

        $html = $buttonBlock->setData($data)->toHtml();
        return $html;
    }
    
}

<?php
namespace Themecafe\CustomerRegister\Block\Config;
use Magento\Backend\Model\UrlInterface;

class Test extends \Magento\Config\Block\System\Config\Form\Field { 
	
    protected $_blockFactorry;
    protected $_backendUrl;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        UrlInterface $backendUrl,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_blockFactory = $blockFactory;
        $this->_backendUrl = $backendUrl;
    }
	
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $block = $this->_blockFactory->createBlock('\Magento\Backend\Block\Widget\Button');
        $params = ['website' => $block->getRequest()->getParam('website')];
        $url = $this->_backendUrl->getUrl("themecafe_customerregister/actions/testSending", $params);
        $html = $block->setType('button')
                    ->setClass('scalable')
                    ->setLabel('Run Now !')
                    ->setOnClick('if (confirm(\''.__('Have you saved your configuration?').'\')) { setLocation(\''.$url . '\' ) }')
                    ->toHtml();
        
        return $html;
        
    }
    
}


<?php
namespace Themecafe\CustomerRegister\Controller\Adminhtml\Actions;

class DownloadTablecells extends \Magento\Backend\App\Action 
{
    public function __construct(
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->resultRawFactory      = $resultRawFactory;
        $this->directoryList = $directoryList;
        parent::__construct($context);
    }
    public function execute()
    {
        $baseurl = $this->directoryList->getPath('app');
        $fullPath = $baseurl.'/code/Themecafe/CustomerRegister/view/adminhtml/web/file/sample.csv';
        if (file_exists($fullPath)) {
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="'.basename($fullPath).'"');
            header('Content-Type: text/csv; charset=utf-8');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fullPath));
            readfile($fullPath);
        }
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw;
    }
}

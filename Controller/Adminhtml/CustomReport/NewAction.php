<?php
namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

class NewAction extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'DEG_CustomReports:customreports';       
    protected $resultPageFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;        
        return parent::__construct($context);
    }
    
    public function execute()
    {
        //Call page factory to render layout and page content
        $resultPage = $this->resultPageFactory->create();
        //Set the menu which will be active for this page
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);

        //Set the header title of grid
        $resultPage->getConfig()->getTitle()->prepend(__('New Report'));
        return $resultPage;
    }    
}

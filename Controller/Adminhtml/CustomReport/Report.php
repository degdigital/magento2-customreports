<?php
namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

class Report extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE='DEG_CustomReports::customreports_view_report';

    private $resultPageFactory;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * Report constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param Builder $builder
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \DEG\CustomReports\Controller\Adminhtml\CustomReport\Builder $builder)
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->builder = $builder;
        return parent::__construct($context);
    }

    public function execute()
    {
        $customReport = $this->builder->build($this->getRequest());
        //Call page factory to render layout and page content
        $resultPage = $this->resultPageFactory->create();
        //Set the menu which will be active for this page
        $resultPage->setActiveMenu('DEG_CustomReports::customreports');

        //Set the header title of grid
        $resultPage->getConfig()->getTitle()->prepend($customReport->getReportName());
        return $resultPage;
    }
}

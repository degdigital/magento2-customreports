<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\AutomatedExport;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Report extends Action
{
    const ADMIN_RESOURCE = 'DEG_CustomReports::automatedexport_view_report';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * Report constructor.
     *
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param Builder                                    $builder
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Builder $builder
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->builder = $builder;

        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $customReport = $this->builder->build($this->getRequest());
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DEG_CustomReports::automatedexport');
        $resultPage->getConfig()->getTitle()->prepend($customReport->getReportName());

        return $resultPage;
    }
}

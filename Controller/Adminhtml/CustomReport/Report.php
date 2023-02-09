<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Report extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'DEG_CustomReports::customreport_view_report';

    public function __construct(
        Context $context,
        protected PageFactory $resultPageFactory,
        protected Builder $builder
    ) {
        return parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $customReport = $this->builder->build($this->getRequest());
        if (!$customReport->getId()) {
            $this->messageManager->addErrorMessage(__('A custom report with the provided ID does not exist.'));
            $redirect = $this->resultRedirectFactory->create();
            $redirect->setPath('deg_customreports/customreport/listing');

            return $redirect;
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DEG_CustomReports::customreport');
        $resultPage->getConfig()->getTitle()->prepend($customReport->getReportName());

        return $resultPage;
    }
}

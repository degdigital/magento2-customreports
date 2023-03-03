<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;

class Delete extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'DEG_CustomReports::customreport_delete';
    /**
     * @var \DEG\CustomReports\Api\CustomReportRepositoryInterface
     */
    private CustomReportRepositoryInterface $customReportRepository;

    public function __construct(
        Context $context,
        CustomReportRepositoryInterface $customReportRepository
    ) {
        parent::__construct($context);
        $this->customReportRepository = $customReportRepository;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute(): Redirect
    {
        $id = $this->getRequest()->getParam('customreport_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $customReport = $this->customReportRepository->getById($id);
                $this->customReportRepository->delete($customReport);
                $this->messageManager->addSuccessMessage(__('You have deleted the report.'));

                return $resultRedirect->setPath('*/*/listing');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['customreport_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can not find a report to delete.'));

        return $resultRedirect->setPath('*/*/listing');
    }
}

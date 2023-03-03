<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;

class Delete extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'DEG_CustomReports::automatedexport_delete';

    public function __construct(
        protected Context $context,
        protected AutomatedExportRepositoryInterface $automatedExportRepository
    ) {
        parent::__construct($context);
    }

    /**
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $id = $this->getRequest()->getParam('object_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $automatedExport = $this->automatedExportRepository->getById($id);
                $this->automatedExportRepository->delete($automatedExport);
                $this->messageManager->addSuccessMessage(__('You have deleted the automated export. Configuration cache has been cleaned to register the dynamic cron deletion.'));

                return $resultRedirect->setPath('*/*/listing');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['automatedexport_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can not find an automated export to delete.'));

        return $resultRedirect->setPath('*/*/listing');
    }
}

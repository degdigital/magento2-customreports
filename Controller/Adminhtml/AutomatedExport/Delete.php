<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Api\DeleteDynamicCronInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'DEG_CustomReports::automatedexport_delete';
    /**
     * @var \DEG\CustomReports\Api\AutomatedExportRepositoryInterface
     */
    private $autoExportReportRepository;
	
	 /**
     * @var \DEG\CustomReports\Api\DeleteDynamicCronInterface
     */
    private $deleteCronConfigData;
	

    public function __construct(
        Context $context,
        AutomatedExportRepositoryInterface $autoExportReportRepository,
		DeleteDynamicCronInterface $deleteCronConfigData
    ) {
        parent::__construct($context);
        $this->autoExportReportRepository = $autoExportReportRepository;
		$this->deleteCronConfigData = $deleteCronConfigData;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('object_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $autoExport = $this->autoExportReportRepository->getById($id);
				if($autoExport->getId()){
					$automatedExportModelName = 'automated_export_'.$autoExport->getId();
					$this->deleteCronConfigData->execute($automatedExportModelName);
					$this->autoExportReportRepository->delete($autoExport);
					$this->messageManager->addSuccessMessage(__('You have deleted the report.'));
				}
				 return $resultRedirect->setPath('*/*/listing');
			
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['automatedexport_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can not find a report to delete.'));

        return $resultRedirect->setPath('*/*/listing');
    }
}

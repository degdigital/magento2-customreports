<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterfaceFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'DEG_CustomReports::automatedexport_save';

    public function __construct(
        protected Action\Context $context,
        protected DataPersistorInterface $dataPersistor,
        protected AutomatedExportRepositoryInterface $automatedExportRepository,
        protected AutomatedExportInterfaceFactory $automatedExportFactory
    ) {
        parent::__construct($context);
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     * @return ResultInterface
     * @throws NoSuchEntityException
     * @throws NoSuchEntityException
     */
    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data['automatedexport_id'])) {
                $data['automatedexport_id'] = null;
            }

            if (empty($data['remote_port'])) {
                $data['remote_port'] = null;
            }

            $automatedExport = $this->automatedExportFactory->create();

            $id = $this->getRequest()->getParam('automatedexport_id');
            if ($id) {
                $automatedExport = $this->automatedExportRepository->getById($id);
            }

            $automatedExport->setData($data);

            try {
                $this->automatedExportRepository->save($automatedExport);
                $this->messageManager->addSuccessMessage(__('You saved the automated export.'));
                if ($automatedExport->getOrigData('cron_expr') != $automatedExport->getCronExpr()) {
                    $this->messageManager->addSuccessMessage(__('Configuration cache has been cleaned to register the updated cron expression.'));
                }
                $this->dataPersistor->clear('deg_customreports_automatedexport');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['automatedexport_id' => $automatedExport->getId(), '_current' => true]
                    );
                }

                return $resultRedirect->setPath('*/*/listing');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('deg_customreports_automatedexport', $data);

            return $resultRedirect->setPath(
                '*/*/edit',
                ['automatedexport_id' => $this->getRequest()->getParam('automatedexport_id')]
            );
        }

        return $resultRedirect->setPath('*/*/listing');
    }
}

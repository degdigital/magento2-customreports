<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Model\CustomReport;
use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'DEG_CustomReports::customreport_save';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var \DEG\CustomReports\Api\CustomReportRepositoryInterface
     */
    private $customReportRepository;

    /**
     * @param Action\Context                                         $context
     * @param DataPersistorInterface                                 $dataPersistor
     * @param \DEG\CustomReports\Api\CustomReportRepositoryInterface $automatedExportRepository
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        CustomReportRepositoryInterface $automatedExportRepository
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->customReportRepository = $automatedExportRepository;
    }

    /**
     * Save action
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data['customreport_id'])) {
                $data['customreport_id'] = null;
            }

            /** @var \DEG\CustomReports\Model\CustomReport $customReport */
            $customReport = $this->_objectManager->create(CustomReport::class);

            $id = $this->getRequest()->getParam('customreport_id');
            if ($id) {
                $customReport = $this->customReportRepository->getById($id);
            }

            $customReport->setData($data);

            try {
                $this->customReportRepository->save($customReport);
                $this->messageManager->addSuccessMessage(__('You saved the report.'));
                $this->dataPersistor->clear('deg_customreports_customreport');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['customreport_id' => $customReport->getId(), '_current' => true]
                    );
                }

                return $resultRedirect->setPath('*/*/listing');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('deg_customreports_customreport', $data);

            return $resultRedirect->setPath(
                '*/*/edit',
                ['customreport_id' => $this->getRequest()->getParam('customreport_id')]
            );
        }

        return $resultRedirect->setPath('*/*/listing');
    }
}

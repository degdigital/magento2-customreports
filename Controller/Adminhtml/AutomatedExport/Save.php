<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterfaceFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Cache\Manager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'DEG_CustomReports::automatedexport_save';

    /**
     * @var DataPersistorInterface
     */
    protected DataPersistorInterface $dataPersistor;
    /**
     * @var \DEG\CustomReports\Api\AutomatedExportRepositoryInterface
     */
    private AutomatedExportRepositoryInterface $automatedExportRepository;
    private AutomatedExportInterfaceFactory $automatedExportFactory;

    private Manager $cacheManager;

    /**
     * @param Action\Context                                              $context
     * @param DataPersistorInterface                                      $dataPersistor
     * @param \DEG\CustomReports\Api\AutomatedExportRepositoryInterface   $automatedExportRepository
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterfaceFactory $automatedExportFactory
     * @param \Magento\Framework\App\Cache\Manager $cacheManager
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        AutomatedExportRepositoryInterface $automatedExportRepository,
        AutomatedExportInterfaceFactory $automatedExportFactory,
        Manager $cacheManager
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->automatedExportRepository = $automatedExportRepository;
        $this->automatedExportFactory = $automatedExportFactory;
        $this->cacheManager = $cacheManager;
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
                    $this->cacheManager->clean(['config']);
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

<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterfaceFactory;
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
    const ADMIN_RESOURCE = 'DEG_CustomReports::automatedexport_save';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var \DEG\CustomReports\Api\AutomatedExportRepositoryInterface
     */
    private $automatedExportRepository;
    private $automatedExportFactory;

    /**
     * @param Action\Context                                              $context
     * @param DataPersistorInterface                                      $dataPersistor
     * @param \DEG\CustomReports\Api\AutomatedExportRepositoryInterface   $automatedExportRepository
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterfaceFactory $automatedExportFactory
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        AutomatedExportRepositoryInterface $automatedExportRepository,
        AutomatedExportInterfaceFactory $automatedExportFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->automatedExportRepository = $automatedExportRepository;
        $this->automatedExportFactory = $automatedExportFactory;
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

            $automatedExport = $this->automatedExportFactory->create();

            $id = $this->getRequest()->getParam('automatedexport_id');
            if ($id) {
                $automatedExport = $this->automatedExportRepository->getById($id);
            }

            $automatedExport->setData($data);

            try {
                $this->automatedExportRepository->save($automatedExport);
                $this->messageManager->addSuccessMessage(__('You saved the automated export.'));
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

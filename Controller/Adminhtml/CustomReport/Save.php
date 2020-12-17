<?php
namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'DEG_CustomReports::customreports_save';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = DEG\CustomReports\Model\CustomReport::STATUS_ENABLED;
            }
            if (empty($data['customreport_id'])) {
                $data['customreport_id'] = null;
            }

            /** @var DEG\CustomReports\Model\CustomReport $model */
            $model = $this->_objectManager->create('DEG\CustomReports\Model\CustomReport');

            $id = $this->getRequest()->getParam('customreport_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the report.'));
                $this->dataPersistor->clear('deg_customreports_customreport');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['customreport_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/listing');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('deg_customreports_customreport', $data);
            return $resultRedirect->setPath('*/*/edit', ['customreport_id' => $this->getRequest()->getParam('customreport_id')]);
        }
        return $resultRedirect->setPath('*/*/listing');
    }
}

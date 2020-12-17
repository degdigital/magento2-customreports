<?php
namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

class Delete extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'DEG_CustomReports::customreports_delete';

    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('object_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $model = $this->_objectManager->create('DEG\CustomReports\Model\CustomReport');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You have deleted the report.'));
                // go to grid
                return $resultRedirect->setPath('*/*/listing');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['customreport_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can not find a report to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/listing');
    }
}

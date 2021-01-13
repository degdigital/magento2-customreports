<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\AutomatedExport;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'DEG_CustomReports::automatedexport_edit';
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Edit constructor.
     *
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;

        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DEG_CustomReports:automatedexport');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Automated Export'));

        return $resultPage;
    }
}

<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class NewAction extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'DEG_CustomReports::customreport_add_new';
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * NewAction constructor.
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
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute(): Page
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DEG_CustomReports::customreport');
        $resultPage->getConfig()->getTitle()->prepend(__('New Report'));

        return $resultPage;
    }
}

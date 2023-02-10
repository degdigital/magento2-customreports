<?php declare(strict_types=1);

namespace DEG\CustomReports\Block\Adminhtml;

use DEG\CustomReports\Registry\CurrentCustomReport;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Grid\Container;

class Report extends Container
{
    public function __construct(
        Context $context,
        protected readonly CurrentCustomReport $currentCustomReportRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->getUrl('*/*/listing');
    }

    /**
     * @return string
     */
    public function getEditUrl(): string
    {
        return $this->getUrl('*/*/edit', ['customreport_id' => $this->currentCustomReportRegistry->get()->getId()]);
    }

    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_report';
        $this->_blockGroup = 'DEG_CustomReports';
        $this->_headerText = __('Report');
        parent::_construct();
        $this->removeButton('add');
        $this->_addBackButton();
        $this->_addEditButton();
    }

    /**
     * @return void
     */
    protected function _addEditButton(): void
    {
        $this->addButton(
            'edit',
            [
                'label' => 'Edit Report',
                'onclick' => 'setLocation(\'' . $this->getEditUrl() . '\')',
                'class' => 'action-secondary'
            ]
        );
    }
}

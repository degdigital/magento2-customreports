<?php declare(strict_types=1);

namespace DEG\CustomReports\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Report extends Container
{
    /**
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->getUrl('*/*/listing');
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
    }
}

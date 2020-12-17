<?php
namespace DEG\CustomReports\Block\Adminhtml;

class Report extends \Magento\Backend\Block\Widget\Grid\Container
{
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

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/listing');
    }
}

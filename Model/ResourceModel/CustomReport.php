<?php
namespace DEG\CustomReports\Model\ResourceModel;

class CustomReport extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('deg_customreports', 'customreport_id');
    }
}

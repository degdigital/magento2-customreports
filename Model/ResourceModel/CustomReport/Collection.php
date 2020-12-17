<?php
namespace DEG\CustomReports\Model\ResourceModel\CustomReport;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('DEG\CustomReports\Model\CustomReport', 'DEG\CustomReports\Model\ResourceModel\CustomReport');
    }
}

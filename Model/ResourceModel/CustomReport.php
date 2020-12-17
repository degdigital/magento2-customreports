<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomReport extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('deg_customreports', 'customreport_id');
    }
}

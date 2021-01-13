<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AutomatedExportLink extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('deg_customreports_automatedexports_link', 'link_id');
    }
}

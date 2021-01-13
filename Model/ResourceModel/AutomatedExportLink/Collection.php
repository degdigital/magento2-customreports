<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\ResourceModel\AutomatedExportLink;

use DEG\CustomReports\Model\ResourceModel\AutomatedExportLink;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\DEG\CustomReports\Model\AutomatedExportLink::class, AutomatedExportLink::class);
    }
}

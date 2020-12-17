<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\ResourceModel\CustomReport;

use DEG\CustomReports\Model\ResourceModel\CustomReport;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\DEG\CustomReports\Model\CustomReport::class, CustomReport::class);
    }
}

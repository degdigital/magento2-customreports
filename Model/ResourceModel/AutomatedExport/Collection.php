<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\ResourceModel\AutomatedExport;

use DEG\CustomReports\Model\ResourceModel\AutomatedExport;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\DEG\CustomReports\Model\AutomatedExport::class, AutomatedExport::class);
    }

    public function addCustomreportIds()
    {
        $this->getSelect()->joinLeft(
            ['link' => $this->getTable('deg_customreports_automatedexports_link')],
            'link.automatedexport_id = main_table.automatedexport_id',
            ['GROUP_CONCAT(link.customreport_id) as customreport_ids']
        );

        return $this;
    }
}

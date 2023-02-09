<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport;

use Magento\Framework\Convert\Excel;

class ExcelXmlConverter extends Excel
{
    public function __construct()
    {
        parent::__construct(new \ArrayIterator());
    }

    public function getXmlHeader($sheetName = ''): string
    {
        return parent::_getXmlHeader($sheetName);
    }

    public function getXmlRow($row): string
    {
        return parent::_getXmlRow($row, false);
    }

    public function getXmlFooter(): string
    {
        return parent::_getXmlFooter();
    }
}

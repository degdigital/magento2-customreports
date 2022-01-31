<?php
declare(strict_types=1);

namespace DEG\CustomReports\Test\Unit\Model;

use DateTime;
use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\AutomatedExportManagement;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use PHPUnit\Framework\TestCase;

class AutomatedExportManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->timezone = $this->createMock(TimezoneInterface::class);
        $date = $this->createMock(DateTime::class);
        $map = [
            ['d', '01'],
            ['Y', '1970'],
        ];
        $date->method('format')->will($this->returnValueMap($map));

        $this->timezone->method('date')->willReturn($date);

        $this->automatedExportManagement = new AutomatedExportManagement($this->timezone);
    }

    public function testGetReplacedFilename()
    {
        $methods = array_merge(get_class_methods(AutomatedExportInterface::class), ['getFilenamePattern']);
        $automatedExport = $this->getMockBuilder(AutomatedExportInterface::class)
            ->addMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
        $automatedExport->method('getFilenamePattern')->willReturn('%reportname%-%Y%-%d%');

        $methods = array_merge(get_class_methods(CustomReportInterface::class), ['getReportName']);
        $customReport = $this->getMockBuilder(CustomReportInterface::class)
            ->addMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
        $customReport->method('getReportName')->willReturn('test');

        $replacedFilename = $this->automatedExportManagement->getReplacedFilename($automatedExport, $customReport);
        $this->assertEquals('test-1970-01', $replacedFilename);
    }
}

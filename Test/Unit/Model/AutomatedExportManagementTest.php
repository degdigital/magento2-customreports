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
        $date->method('format')->will($this->returnCallback(function ($value) {
            switch ($value) {
                case 'd':
                    return '01';
                case 'Y':
                    return '1970';
                default:
                    return '123';
            }
        }));

        $this->timezone->method('date')->willReturn($date);

        $this->automatedExportManagement = new AutomatedExportManagement($this->timezone);
    }

    public function testGetReplacedFilename()
    {
        $automatedExport = $this->createStub(AutomatedExportInterface::class);
        $customReport = $this->createStub(CustomReportInterface::class);

        $automatedExport->method('getFilenamePattern')->willReturn('%reportname%-%Y%-%d%');
        $customReport->method('getReportName')->willReturn('test');

        $replacedFilename = $this->automatedExportManagement->getReplacedFilestem($automatedExport, $customReport);
        $this->assertEquals('test-1970-01', $replacedFilename);
    }
}

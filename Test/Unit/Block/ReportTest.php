<?php
declare(strict_types=1);

namespace Tests\Unit\DEG\CustomReports\Block\Adminhtml;

use DEG\CustomReports\Block\Adminhtml\Report;
use Magento\Backend\Block\Widget\Button\ButtonList;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    protected Report $report;

    protected function setUp(): void
    {
        parent::setUp();
        $this->context = $this->createMock(Context::class);
        $this->url = $this->createMock(UrlInterface::class);
        $this->url->method('getUrl')->willReturn('google.com');
        $this->context->method('getUrlBuilder')->willReturn($this->url);
        $buttonList = $this->createMock(ButtonList::class);
        $this->context->method('getButtonList')->willReturn($buttonList);
        $this->data = [];
        $this->report = new Report($this->context, $this->data, $this->data);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->report);
    }

    public function testGetBackUrl(): void
    {
        $this->assertEquals('google.com', $this->report->getBackUrl());
    }
}

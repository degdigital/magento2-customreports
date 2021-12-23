<?php

namespace DEG\CustomReports\Test\Unit\Block\Adminhtml\AutomatedExport\Edit;

use DEG\CustomReports\Block\Adminhtml\AutomatedExport\Edit\BackButton;
use Magento\Backend\Block\Widget\Context;
use PHPUnit\Framework\TestCase;

class BackButtonTest extends TestCase
{
    /**
     * @var GenericButton
     */
    protected $backButton;

    /**
     * @var Context|Mock
     */
    protected $context;

    /**
     * @var \Magento\Framework\UrlInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $url;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->context = $this->createMock(Context::class);
        $this->url = $this->createMock(\Magento\Framework\UrlInterface::class);
        $this->context->method('getUrlBuilder')->willReturn($this->url);

        $this->backButton = new BackButton($this->context);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->backButton);
        unset($this->context);
    }

    public function testGetButtonData(): void
    {
        $testUrl = 'https//local.local/123456';
        $this->url->method('getUrl')->willReturn($testUrl);

        $this->assertEquals(array_keys($this->backButton->getButtonData()), [
           'label',
            'on_click',
            'class',
            'sort_order'
        ]);
    }
}

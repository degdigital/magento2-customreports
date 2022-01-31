<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

namespace DEG\CustomReports\Test\Unit\Block\Adminhtml\CustomReport\Edit;

use DEG\CustomReports\Block\Adminhtml\CustomReport\Edit\BackButton;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;

class BackButtonTest extends TestCase
{

    /**
     * @var \DEG\CustomReports\Block\Adminhtml\CustomReport\Edit\BackButton
     */
    protected BackButton $backButton;

    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
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
        $this->url = $this->createMock(UrlInterface::class);
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

        $this->assertEquals([
            'label',
            'on_click',
            'class',
            'sort_order',
        ], array_keys($this->backButton->getButtonData()));
    }
}

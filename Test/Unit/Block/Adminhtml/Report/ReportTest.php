<?php

namespace DEG\CustomReports\Test\Unit\Block\Adminhtml\Report;

use DEG\CustomReports\Block\Adminhtml\Report\Export;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\CollectionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use PHPUnit\Framework\TestCase;

class ExportTest extends TestCase
{
    /**
     * @var Export
     */
    protected $export;

    /**
     * @var Context|Mock
     */
    protected $context;

    /**
     * @var CollectionFactory|Mock
     */
    protected $collectionFactory;

    /**
     * @var TimezoneInterface|Mock
     */
    protected $timeZone;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Magento\Framework\Filesystem|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $fileSystem;

    /**
     * @var \Magento\Framework\View\LayoutInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $layout;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->context = $this->createMock(Context::class);

        $this->fileSystem = $this->createMock(\Magento\Framework\Filesystem::class);
        $this->context->method('getFilesystem')->willReturn($this->fileSystem);

        $this->layout = $this->createMock(\Magento\Framework\View\LayoutInterface::class);
        $this->context->method('getLayout')->willReturn($this->layout);

        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->timeZone = $this->createMock(TimezoneInterface::class);
        $this->data = [];
        $this->export = new Export($this->context, $this->collectionFactory, $this->timeZone, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->export);
        unset($this->context);
        unset($this->collectionFactory);
        unset($this->timeZone);
        unset($this->data);
    }

    public function test_prepareLayout(): void
    {
        $this->export->_prepareLayout();
    }

    public function testLazyPrepareLayout(): void
    {
        $blockMock = $this->createMock(\Magento\Framework\View\Element\Template::class);
        $this->layout->method('createBlock')->willReturn($blockMock);

        $this->layout->method('getParentName')->willReturn('testBlock');
        $abstractBlockMock = $this->createMock(\Magento\Framework\View\Element\AbstractBlock::class);
        $this->layout->method('getBlock')->willReturn($abstractBlockMock);

        $this->export->lazyPrepareLayout();
    }
}

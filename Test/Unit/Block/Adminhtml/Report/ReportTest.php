<?php
declare(strict_types=1);

namespace DEG\CustomReports\Test\Unit\Block\Adminhtml\Report;

use DEG\CustomReports\Block\Adminhtml\Report\Export;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\CollectionFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use PHPUnit\Framework\TestCase;

class ExportTest extends TestCase
{
    /**
     * @var Export
     */
    protected Export $export;

    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $context;

    /**
     * @var CollectionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionFactory;

    /**
     * @var TimezoneInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $timeZone;

    /**
     * @var array
     */
    protected array $data;

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

        $this->fileSystem = $this->createMock(Filesystem::class);
        $this->context->method('getFilesystem')->willReturn($this->fileSystem);

        $this->layout = $this->createMock(LayoutInterface::class);
        $this->context->method('getLayout')->willReturn($this->layout);

        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->timeZone = $this->createMock(TimezoneInterface::class);
        $this->data = [];
        $this->export = new Export($this->context, $this->collectionFactory, $this->data);
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

    /**
     * @noinspection PhpExpressionResultUnusedInspection
     */
    public function testPrepareLayout(): void
    {
        $this->export->_prepareLayout();
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testLazyPrepareLayout(): void
    {
        $blockMock = $this->createMock(Template::class);
        $this->layout->method('createBlock')->willReturn($blockMock);

        $this->layout->method('getParentName')->willReturn('testBlock');
        $abstractBlockMock = $this->createMock(AbstractBlock::class);
        $this->layout->method('getBlock')->willReturn($abstractBlockMock);

        $this->export->lazyPrepareLayout();
    }
}

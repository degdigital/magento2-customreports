<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

namespace DEG\CustomReports\Test\Unit\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Controller\Adminhtml\AutomatedExport\Edit;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;
use Magento\Framework\View\Result\Page;
use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class EditTest extends TestCase
{

    private $resultPageFactory;

    private $contextMock;

    private $titleMock;

    public function setUp(): void
    {
        $this->titleMock = $this->getMockTitle();
        $this->resultPageFactory = $this->getResultPageFactory();

        $this->contextMock = $this->createMock(Context::class);
    }

    protected function getResultPageFactory()
    {
        $mockPageResult = $this
            ->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMockForAbstractClass();

        $mockPage = $this
            ->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->setMethods(['setActiveMenu', 'getConfig'])
            ->getMockForAbstractClass();

        $mockConfig = $this
            ->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTitle'])
            ->getMockForAbstractClass();

        $mockPageResult->expects($this->any())
            ->method('create')
            ->willReturn($mockPage);

        $mockPage->expects($this->any())
            ->method('getConfig')
            ->willReturn($mockConfig);

        $mockConfig->expects($this->any())
            ->method('getTitle')
            ->willReturn($this->titleMock);

        return $mockPageResult;
    }

    protected function getMockTitle()
    {
        return $this
            ->getMockBuilder(Title::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepend'])
            ->getMockForAbstractClass();
    }

    protected function getModel(): object
    {
        $objectManager = new ObjectManager($this);

        return $objectManager->getObject(
            Edit::class,
            [
                "context" => $this->contextMock,
                "resultPageFactory" => $this->resultPageFactory,
            ]
        );
    }

    public function testExecuteExceptionType(): void
    {
        $this->titleMock->expects($this->at(0))
            ->method('prepend')
            ->willReturn(__('New Report'));

        $this->getModel()->execute();
    }
}

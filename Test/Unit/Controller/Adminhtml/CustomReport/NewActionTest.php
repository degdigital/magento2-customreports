<?php

namespace DEG\CustomReports\Test\Unit\Controller\Adminhtml\CustomReport;

use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use DEG\CustomReports\Controller\Adminhtml\CustomReport\Builder;

class NewActionTest extends TestCase
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
            ->getMockBuilder(\Magento\Framework\View\Result\Page::class)
            ->disableOriginalConstructor()
            ->setMethods(['setActiveMenu', 'getConfig'])
            ->getMockForAbstractClass();


        $mockConfig = $this
            ->getMockBuilder(\Magento\Framework\View\Page\Config::class)
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
        $mockTitle = $this
            ->getMockBuilder(\Magento\Framework\View\Page\Title::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepend'])
            ->getMockForAbstractClass();

        return $mockTitle;
    }

    protected function getModel()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $model = $objectManager->getObject(
            \DEG\CustomReports\Controller\Adminhtml\CustomReport\NewAction::class,
            [
                "context" => $this->contextMock,
                "resultPageFactory" => $this->resultPageFactory,
            ]
        );

        return $model;
    }

    public function testExecuteExeptionType(): void
    {
        $this->titleMock->expects($this->at(0))
            ->method('prepend')
            ->willReturn(__('New Report'));

        $this->getModel()->execute();
    }
}

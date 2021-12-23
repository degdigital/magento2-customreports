<?php

namespace DEG\CustomReports\Test\Unit\Controller\Adminhtml\CustomReport;

use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use DEG\CustomReports\Controller\Adminhtml\CustomReport\Builder;

use DEG\CustomReports\Model\CustomReportFactory;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\Registry;

class BuilderTest extends TestCase
{
    /**
     * @var CustomReportFactory
     */
    private $customReport;

    /**
     * @var CustomReportFactory
     */
    private $customReportFactory;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Registry
     */
    private $registry;

    private $requestMock;

    public function setUp(): void
    {
        $this->customReport = $this->getCustomReport();
        $this->customReportFactory = $this->getCustomReportFactory();
        $this->logger = $this->getLogger();
        $this->registry = $this->getRegistry();
        $this->requestMock = $this->getMockForAbstractClass(
            \Magento\Framework\App\RequestInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['getParam']
        );
    }

    protected function getCustomReportFactory()
    {
        $mockHelper = $this
            ->getMockBuilder(CustomReportFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $mockHelper->expects($this->any())
            ->method('create')
            ->willReturn($this->customReport);

        return $mockHelper;
    }

    protected function getLogger()
    {
        $mockHelper = $this
            ->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMockForAbstractClass();

        return $mockHelper;
    }

    protected function getRegistry()
    {
        $mockHelper = $this
            ->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->setMethods(['register'])
            ->getMockForAbstractClass();

        return $mockHelper;
    }

    protected function getCustomReport()
    {
        $mockHelper = $this
            ->getMockBuilder(\DEG\CustomReports\Model\CustomReport::class)
            ->disableOriginalConstructor()
            ->setMethods(['load'])
            ->getMockForAbstractClass();

        return $mockHelper;
    }


    protected function getModel()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $model = $objectManager->getObject(
            \DEG\CustomReports\Controller\Adminhtml\CustomReport\Builder::class,
            [
                "customReportFactory" => $this->customReportFactory,
                "logger" => $this->logger,
                "registry" => $this->registry
            ]
        );

        return $model;
    }

    public function testBuild(): void
    {
        $this->requestMock
            ->expects($this->at(0))
            ->method('getParam')
            ->willReturn(122);

        $this->getModel()->build($this->requestMock);
    }


    public function testBuildNullId(): void
    {
        $this->requestMock
            ->expects($this->at(0))
            ->method('getParam')
            ->willReturn(null);

        $this->getModel()->build($this->requestMock);
    }


    public function testBuildLogger(): void
    {
        $this->requestMock
            ->expects($this->at(0))
            ->method('getParam')
            ->willReturn(10);

        $this->getModel()->build($this->requestMock);
    }
}

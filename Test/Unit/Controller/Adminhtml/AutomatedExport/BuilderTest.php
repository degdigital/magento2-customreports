<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

namespace DEG\CustomReports\Test\Unit\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Controller\Adminhtml\AutomatedExport\Builder;
use DEG\CustomReports\Model\CustomReport;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

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
            RequestInterface::class,
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
        return $this
            ->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical'])
            ->getMockForAbstractClass();
    }

    protected function getRegistry()
    {
        return $this
            ->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->setMethods(['register'])
            ->getMockForAbstractClass();
    }

    protected function getCustomReport()
    {
        return $this
            ->getMockBuilder(CustomReport::class)
            ->disableOriginalConstructor()
            ->setMethods(['load'])
            ->getMockForAbstractClass();
    }

    protected function getModel(): object
    {
        $objectManager = new ObjectManager($this);

        return $objectManager->getObject(
            Builder::class,
            [
                "customReportFactory" => $this->customReportFactory,
                "logger" => $this->logger,
                "registry" => $this->registry,
            ]
        );
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

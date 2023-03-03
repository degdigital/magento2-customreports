<?php
declare(strict_types=1);

namespace DEG\CustomReports\Test\Unit\Model\Service;

use DEG\CustomReports\Model\AutomatedExport;
use DEG\CustomReports\Model\Service\DeleteDynamicCron;
use Magento\Framework\App\Cache\Manager;
use Magento\Framework\App\Config\Value;
use Magento\Framework\App\Config\ValueFactory;
use PHPUnit\Framework\TestCase;

class DeleteDynamicCronTest extends TestCase
{
    /**
     * @var DeleteDynamicCron
     */
    protected DeleteDynamicCron $deleteDynamicCron;

    /**
     * @var ValueFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configValueFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->configValueFactory = $this->createMock(ValueFactory::class);
        $this->cacheManager = $this->createStub(Manager::class);

        $this->deleteDynamicCron = new DeleteDynamicCron($this->configValueFactory, $this->cacheManager);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->deleteDynamicCron);
        unset($this->configValueFactory);
    }

    /**
     * @throws \Exception
     */
    public function testExecute(): void
    {
        $automatedExport = $this->createMock(AutomatedExport::class);


        $valueMock = $this->getMockBuilder(Value::class)
            ->setMethods(['load', 'setValue', 'setPath', 'save', 'delete'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->configValueFactory->method('create')->willReturn($valueMock);

        $valueMock->method('load')->willReturnSelf();
        $valueMock->method('delete')->willReturnSelf();

        $this->deleteDynamicCron->execute($automatedExport);
    }
}

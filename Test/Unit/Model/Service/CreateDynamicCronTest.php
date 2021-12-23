<?php

namespace Tests\Unit\DEG\CustomReports\Model\Service;

use DEG\CustomReports\Model\Service\CreateDynamicCron;
use Magento\Framework\App\Config\ValueFactory;
use PHPUnit\Framework\TestCase;

class CreateDynamicCronTest extends TestCase
{
    /**
     * @var CreateDynamicCron
     */
    protected $createDynamicCron;

    /**
     * @var ValueFactory|Mock
     */
    protected $configValueFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->configValueFactory = $this->createMock(ValueFactory::class);
        $this->createDynamicCron = new CreateDynamicCron($this->configValueFactory);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->createDynamicCron);
        unset($this->configValueFactory);
    }

    public function testExecute(): void
    {
        $automatedExport = $this->createMock(\DEG\CustomReports\Model\AutomatedExport::class);

        $valueMock = $this->getMockBuilder(\Magento\Framework\App\Config\Value::class)
            ->setMethods(['load', 'setValue', 'setPath', 'save'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->configValueFactory->method('create')->willReturn($valueMock);

        $valueMock->method('load')->willReturnSelf();
        $valueMock->method('setValue')->willReturnSelf();
        $valueMock->method('setPath')->willReturnSelf();
        $valueMock->method('save')->willReturnSelf();


        $this->createDynamicCron->execute($automatedExport);
    }
}

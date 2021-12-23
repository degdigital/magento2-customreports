<?php

namespace DEG\CustomReports\Test\Unit\Model\Service;

use DEG\CustomReports\Model\Service\DeleteDynamicCron;
use Magento\Framework\App\Config\ValueFactory;
use PHPUnit\Framework\TestCase;

class DeleteDynamicCronTest extends TestCase
{
    /**
     * @var DeleteDynamicCron
     */
    protected $deleteDynamicCron;

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
        $this->deleteDynamicCron = new DeleteDynamicCron($this->configValueFactory);
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

    public function testExecute(): void
    {
        $automatedExportModelName = 'pathModel';

        $valueMock = $this->getMockBuilder(\Magento\Framework\App\Config\Value::class)
            ->setMethods(['load', 'setValue', 'setPath', 'save', 'delete'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->configValueFactory->method('create')->willReturn($valueMock);

        $valueMock->method('load')->willReturnSelf();
        $valueMock->method('delete')->willReturnSelf();

        $this->deleteDynamicCron->execute($automatedExportModelName);
    }
}

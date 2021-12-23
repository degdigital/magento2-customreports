<?php

namespace DEG\CustomReports\Test\Unit\Model\AutomatedExport;

use DEG\CustomReports\Model\AutomatedExport\DataProvider;
use DEG\CustomReports\Model\ResourceModel\AutomatedExport\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use PHPUnit\Framework\TestCase;

class DataProviderTest extends TestCase
{
    /**
     * @var DataProvider
     */
    protected $dataProvider;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $primaryFieldName;

    /**
     * @var string
     */
    protected $requestFieldName;

    /**
     * @var CollectionFactory|Mock
     */
    protected $collectionFactory;

    /**
     * @var DataPersistorInterface|Mock
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var \DEG\CustomReports\Model\ResourceModel\AutomatedExport\Collection|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->name = '42';
        $this->primaryFieldName = '42';
        $this->requestFieldName = '42';

        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->collectionMock = $this->createMock
        (\DEG\CustomReports\Model\ResourceModel\AutomatedExport\Collection::class);
        $this->collectionFactory->method('create')->willReturn($this->collectionMock);

        $this->dataPersistor = $this->createMock(DataPersistorInterface::class);
        $this->meta = [];
        $this->data = [];
        $this->dataProvider = new DataProvider($this->name, $this->primaryFieldName, $this->requestFieldName, $this->collectionFactory, $this->dataPersistor, $this->meta, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->dataProvider);
        unset($this->name);
        unset($this->primaryFieldName);
        unset($this->requestFieldName);
        unset($this->collectionFactory);
        unset($this->dataPersistor);
        unset($this->meta);
        unset($this->data);
    }

    public function testPrepareMeta(): void
    {
        $this->dataProvider->prepareMeta([]);
    }

    public function testGetData(): void
    {
        $this->collectionMock->method('addCustomreportIds')->willReturnSelf();

        $dataObjectMock = $this->createMock(\Magento\Framework\DataObject::class);
        $this->collectionMock->method('getItems')->willReturn([$dataObjectMock]);

        $this->dataPersistor->method('get')->willReturn(1);
        $this->collectionMock->method('getNewEmptyItem')->willReturn($dataObjectMock);

        $this->dataProvider->getData();
    }
}

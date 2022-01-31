<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

namespace DEG\CustomReports\Test\Unit\Model\CustomReport;

use DEG\CustomReports\Model\CustomReport\DataProvider;
use DEG\CustomReports\Model\ResourceModel\CustomReport\Collection;
use DEG\CustomReports\Model\ResourceModel\CustomReport\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\DataObject;
use PHPUnit\Framework\TestCase;

class DataProviderTest extends TestCase
{
    /**
     * @var DataProvider
     */
    protected DataProvider $dataProvider;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $primaryFieldName;

    /**
     * @var string
     */
    protected string $requestFieldName;

    /**
     * @var CollectionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionFactory;

    /**
     * @var DataPersistorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected array $meta;

    /**
     * @var array
     */
    protected array $data;

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
        $this->collectionMock = $this->createMock(Collection::class);
        $this->collectionFactory->method('create')->willReturn($this->collectionMock);
        $this->dataPersistor = $this->createMock(DataPersistorInterface::class);
        $this->meta = [];
        $this->data = [];
        $this->dataProvider = new DataProvider(
            $this->name,
            $this->primaryFieldName,
            $this->requestFieldName,
            $this->collectionFactory,
            $this->dataPersistor,
            $this->meta,
            $this->data
        );
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

    /**
     * @noinspection PhpExpressionResultUnusedInspection
     */
    public function testPrepareMeta(): void
    {
        $this->dataProvider->prepareMeta([]);
    }

    public function testGetData(): void
    {
        $dataObjectMock = $this->createMock(DataObject::class);
        $this->collectionMock->method('getItems')->willReturn([$dataObjectMock]);

        $this->dataPersistor->method('get')->willReturn(1);
        $this->collectionMock->method('getNewEmptyItem')->willReturn($dataObjectMock);

        $this->dataProvider->getData();
    }
}

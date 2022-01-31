<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Model\ResourceModel\AutomatedExport\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var \DEG\CustomReports\Model\ResourceModel\AutomatedExport\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected DataPersistorInterface $dataPersistor;

    /**
     * @var array
     */
    protected array $loadedData = [];

    /**
     * @var \DEG\CustomReports\Api\AutomatedExportRepositoryInterface
     */
    private AutomatedExportRepositoryInterface $automatedExportRepository;

    /**
     * @param string                                                                   $name
     * @param string                                                                   $primaryFieldName
     * @param string                                                                   $requestFieldName
     * @param \DEG\CustomReports\Model\ResourceModel\AutomatedExport\CollectionFactory $collectionFactory
     * @param \Magento\Framework\App\Request\DataPersistorInterface                    $dataPersistor
     * @param \DEG\CustomReports\Api\AutomatedExportRepositoryInterface                $automatedExportRepository
     * @param array                                                                    $meta
     * @param array                                                                    $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        AutomatedExportRepositoryInterface $automatedExportRepository,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->meta = $this->prepareMeta($this->meta);
        $this->automatedExportRepository = $automatedExportRepository;
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     *
     * @return array
     */
    public function prepareMeta(array $meta): array
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): ?array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->addCustomreportIds()->getItems();

        foreach ($items as $item) {
            try {
                if ($automatedExport = $this->automatedExportRepository->getById($item->getId())) {
                    $this->loadedData[$item->getId()] = $automatedExport->getData();
                }
            } catch (NoSuchEntityException $exception) { // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock.DetectedCatch
            }
        }

        $data = $this->dataPersistor->get('deg_customreports_automatedexport');
        if (!empty($data)) {
            $item = $this->collection->getNewEmptyItem();
            $item->setData($data);
            $this->loadedData[$item->getId()] = $item->getData();
            $this->dataPersistor->clear('deg_customreports_automatedexport');
        }

        return $this->loadedData;
    }
}

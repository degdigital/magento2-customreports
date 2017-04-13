<?php
namespace DEG\CustomReports\Ui\Component\Listing\DataProviders\Deg\Customreports;

class Customreports extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * Customreports constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \DEG\CustomReports\Model\ResourceModel\CustomReport\CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \DEG\CustomReports\Model\ResourceModel\CustomReport\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}

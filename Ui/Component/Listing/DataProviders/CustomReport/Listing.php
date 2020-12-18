<?php declare(strict_types=1);

namespace DEG\CustomReports\Ui\Component\Listing\DataProviders\CustomReport;

use DEG\CustomReports\Model\ResourceModel\CustomReport\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class Listing extends AbstractDataProvider
{
    /**
     * Customreports constructor.
     *
     * @param string                                                                $name
     * @param string                                                                $primaryFieldName
     * @param string                                                                $requestFieldName
     * @param \DEG\CustomReports\Model\ResourceModel\CustomReport\CollectionFactory $collectionFactory
     * @param array                                                                 $meta
     * @param array                                                                 $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}

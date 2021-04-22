<?php
declare(strict_types=1);

namespace DEG\CustomReports\Model\Config\Source;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;

class AutomatedExports implements OptionSourceInterface
{
    private $searchCriteriaBuilder;
    private $automatedExportRepository;

    /**
     * AutomatedExports constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteriaBuilder              $searchCriteriaBuilder
     * @param \DEG\CustomReports\Api\AutomatedExportRepositoryInterface $automatedExportRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AutomatedExportRepositoryInterface $automatedExportRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->automatedExportRepository = $automatedExportRepository;
    }

    public function toOptionArray(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $automatedExports = $this->automatedExportRepository->getList($searchCriteria);

        $options = [];

        foreach ($automatedExports as $automatedExport) {
            $options[] = ['value' => $automatedExport->getId(), 'label' => $automatedExport->getName()];
        }

        return $options;
    }
}

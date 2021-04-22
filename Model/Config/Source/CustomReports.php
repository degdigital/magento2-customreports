<?php
declare(strict_types=1);

namespace DEG\CustomReports\Model\Config\Source;

use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;

class CustomReports implements OptionSourceInterface
{
    private $customReportRepository;
    private $searchCriteriaBuilder;

    public function __construct(
        CustomReportRepositoryInterface $customReportRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->customReportRepository = $customReportRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function toOptionArray(): array
    {
        /** @var \DEG\CustomReports\Model\CustomReport $customReport */

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $customReports = $this->customReportRepository->getList($searchCriteria);

        $options = [];

        foreach ($customReports->getItems() as $customReport) {
            $options[] = ['value' => $customReport->getId(), 'label' => $customReport->getReportName()];
        }

        return $options;
    }
}

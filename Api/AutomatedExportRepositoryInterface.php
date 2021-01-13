<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\AutomatedExportInterface;
use DEG\CustomReports\Model\AutomatedExport;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface AutomatedExportRepositoryInterface
{
    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     *
     * @return \DEG\CustomReports\Api\Data\AutomatedExportInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(AutomatedExportInterface $automatedExport): AutomatedExportInterface;

    /**
     * @param $id
     *
     * @return \DEG\CustomReports\Model\AutomatedExport
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id): AutomatedExport;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface;

    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportInterface $automatedExport
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(AutomatedExportInterface $automatedExport): bool;

    /**
     * @param $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id): bool;
}

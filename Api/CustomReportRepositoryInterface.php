<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use DEG\CustomReports\Model\CustomReport;
use Magento\Framework\Api\SearchCriteriaInterface;

interface CustomReportRepositoryInterface
{
    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $customReport
     *
     * @return \DEG\CustomReports\Api\Data\CustomReportInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(CustomReportInterface $customReport): CustomReportInterface;

    /**
     * @param $id
     *
     * @return \DEG\CustomReports\Model\CustomReport
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id): CustomReport;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     *
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $customReport
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(CustomReportInterface $customReport): bool;

    /**
     * @param $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id): bool;
}

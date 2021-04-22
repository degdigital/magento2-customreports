<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\AutomatedExportLinkInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface AutomatedExportLinkRepositoryInterface
{
    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportLinkInterface $automatedExportLink
     *
     * @return \DEG\CustomReports\Api\Data\AutomatedExportLinkInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(AutomatedExportLinkInterface $automatedExportLink): AutomatedExportLinkInterface;

    /**
     * @param $id
     *
     * @return \DEG\CustomReports\Api\Data\AutomatedExportLinkInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id): AutomatedExportLinkInterface;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * @param \DEG\CustomReports\Api\Data\AutomatedExportLinkInterface $automatedExportLink
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(AutomatedExportLinkInterface $automatedExportLink): bool;

    /**
     * @param $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id): bool;
}

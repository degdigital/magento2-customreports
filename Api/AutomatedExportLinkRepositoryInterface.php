<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\AutomatedExportLinkInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface AutomatedExportLinkRepositoryInterface
{
    /**
     * @param AutomatedExportLinkInterface $automatedExportLink
     * @return AutomatedExportLinkInterface
     * @throws CouldNotSaveException
     */
    public function save(AutomatedExportLinkInterface $automatedExportLink): AutomatedExportLinkInterface;

    /**
     * @param $id
     * @return AutomatedExportLinkInterface
     * @throws NoSuchEntityException
     */
    public function getById($id): AutomatedExportLinkInterface;

    /**
     * @param SearchCriteriaInterface $criteria
     *
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface;

    /**
     * @param AutomatedExportLinkInterface $automatedExportLink
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(AutomatedExportLinkInterface $automatedExportLink): bool;

    /**
     * @param $id
     *
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id): bool;
}

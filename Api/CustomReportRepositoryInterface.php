<?php

namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface CustomReportRepositoryInterface
{
    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $page
     *
     * @return mixed
     */
    public function save(CustomReportInterface $page);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getById($id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     *
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $page
     *
     * @return mixed
     */
    public function delete(CustomReportInterface $page);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteById($id);
}

<?php
namespace DEG\CustomReports\Api;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface CustomReportRepositoryInterface
{
    public function save(CustomReportInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(CustomReportInterface $page);

    public function deleteById($id);
}

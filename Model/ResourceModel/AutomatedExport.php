<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\ResourceModel;

use DEG\CustomReports\Api\AutomatedExportLinkRepositoryInterface;
use DEG\CustomReports\Api\CreateDynamicCronInterface;
use DEG\CustomReports\Model\AutomatedExportLinkFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class AutomatedExport extends AbstractDb
{
    private $automatedExportLinkFactory;
    private $automatedExportLinkRepository;
    private $searchCriteriaBuilder;
    private $setDynamicCronService;

    public function __construct(
        Context $context,
        AutomatedExportLinkFactory $automatedExportLinkFactory,
        AutomatedExportLinkRepositoryInterface $automatedExportLinkRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CreateDynamicCronInterface $setDynamicCronService,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->automatedExportLinkFactory = $automatedExportLinkFactory;
        $this->automatedExportLinkRepository = $automatedExportLinkRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->setDynamicCronService = $setDynamicCronService;
    }

    protected function _construct()
    {
        $this->_init('deg_customreports_automatedexports', 'automatedexport_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel|\DEG\CustomReports\Model\AutomatedExport $object
     *
     * @return \DEG\CustomReports\Model\ResourceModel\AutomatedExport
     */
    protected function _beforeSave(AbstractModel $object): AutomatedExport
    {
        $exportTypes = $object->getExportTypes();
        if (is_array($exportTypes)) {
            $object->setExportTypes(implode(',', $exportTypes));
        }

        $fileTypes = $object->getFileTypes();
        if (is_array($fileTypes)) {
            $object->setFileTypes(implode(',', $fileTypes));
        }

        return parent::_beforeSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel|\DEG\CustomReports\Model\AutomatedExport $object
     *
     * @return \DEG\CustomReports\Model\ResourceModel\AutomatedExport
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    protected function _afterSave(AbstractModel $object): AutomatedExport
    {
        $this->saveAutomatedExportLinks($object);
        $this->setDynamicCron($object);

        return parent::_afterSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel|\DEG\CustomReports\Model\AutomatedExport $object
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    private function saveAutomatedExportLinks(AbstractModel $object): void
    {
        /** @var $automatedExportLink \DEG\CustomReports\Api\Data\AutomatedExportLinkInterface */

        $customReportIds = $object->getCustomreportIds();
        if (is_array($customReportIds)) {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('automatedexport_id', $object->getId())
                ->create();

            $automatedExportLinks = $this->automatedExportLinkRepository->getList($searchCriteria);
            foreach ($automatedExportLinks->getItems() as $automatedExportLink) {
                $this->automatedExportLinkRepository->delete($automatedExportLink);
            }

            foreach ($customReportIds as $customReportId) {
                $automatedExportLink = $this->automatedExportLinkFactory->create();
                $automatedExportLink->setCustomreportId($customReportId);
                $automatedExportLink->setAutomatedexportId($object->getId());
                $this->automatedExportLinkRepository->save($automatedExportLink);
            }
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel|\DEG\CustomReports\Model\AutomatedExport $object
     */
    private function setDynamicCron(AbstractModel $object)
    {
        return $this->setDynamicCronService->execute($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel|\DEG\CustomReports\Model\AutomatedExport $object
     *
     * @return \DEG\CustomReports\Model\ResourceModel\AutomatedExport
     */
    protected function _afterLoad(AbstractModel $object)
    {
        /** @var $automatedExportLink \DEG\CustomReports\Api\Data\AutomatedExportLinkInterface */

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('automatedexport_id', $object->getId())
            ->create();
        $automatedExportLinks = $this->automatedExportLinkRepository->getList($searchCriteria);
        if ($automatedExportLinks->getTotalCount()) {
            $customReportIds = [];
            foreach ($automatedExportLinks->getItems() as $automatedExportLink) {
                $customReportIds[] = $automatedExportLink->getCustomreportId();
            }
            $object->setCustomreportIds($customReportIds);
        }

        $exportTypes = $object->getExportTypes();
        if (!is_array($exportTypes)) {
            $object->setExportTypes(explode(',', $exportTypes));
        }

        $fileTypes = $object->getFileTypes();
        if (!is_array($fileTypes)) {
            $object->setFileTypes(explode(',', $fileTypes));
        }

        return parent::_afterLoad($object);
    }
}

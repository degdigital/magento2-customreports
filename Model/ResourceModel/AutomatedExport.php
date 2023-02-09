<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\ResourceModel;

use DEG\CustomReports\Api\AutomatedExportLinkRepositoryInterface;
use DEG\CustomReports\Api\CreateDynamicCronInterface;
use DEG\CustomReports\Api\Data\AutomatedExportLinkInterface;
use DEG\CustomReports\Model\AutomatedExport as AutomatedExportModel;
use DEG\CustomReports\Model\AutomatedExportLinkFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class AutomatedExport extends AbstractDb
{
    public function __construct(
        Context $context,
        protected AutomatedExportLinkFactory $automatedExportLinkFactory,
        protected AutomatedExportLinkRepositoryInterface $automatedExportLinkRepository,
        protected SearchCriteriaBuilder $searchCriteriaBuilder,
        protected CreateDynamicCronInterface $createDynamicCronService,
        protected EncryptorInterface $encryptor,
        ?string $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('deg_customreports_automatedexports', 'automatedexport_id');
    }

    /**
     * @param AbstractModel|AutomatedExportModel $object
     * @return AutomatedExport
     */
    protected function _beforeSave(AbstractModel|AutomatedExportModel $object): AutomatedExport
    {
        $exportTypes = $object->getExportTypes();
        if (is_array($exportTypes)) {
            $object->setExportTypes(implode(',', $exportTypes));
        }

        $fileTypes = $object->getFileTypes();
        if (is_array($fileTypes)) {
            $object->setFileTypes(implode(',', $fileTypes));
        }

        $object->setRemotePassword($this->encryptor->encrypt($object->getRemotePassword()));

        return parent::_beforeSave($object);
    }

    /**
     * @param AbstractModel|AutomatedExportModel $object
     * @return AutomatedExport
     * @throws CouldNotDeleteException
     * @throws CouldNotSaveException
     */
    protected function _afterSave(AbstractModel|AutomatedExportModel $object): AutomatedExport
    {
        $this->saveAutomatedExportLinks($object);
        $this->createDynamicCron($object);

        return parent::_afterSave($object);
    }

    /**
     * @param AbstractModel|AutomatedExportModel $object
     * @throws CouldNotDeleteException
     * @throws CouldNotSaveException
     */
    protected function saveAutomatedExportLinks(AbstractModel|AutomatedExportModel $object): void
    {
        /** @var AutomatedExportLinkInterface $automatedExportLink */

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
                $automatedExportLink->setCustomreportId((int)$customReportId);
                $automatedExportLink->setAutomatedexportId((int)$object->getId());
                $this->automatedExportLinkRepository->save($automatedExportLink);
            }
        }
    }

    /**
     * @param AbstractModel|AutomatedExportModel $object
     */
    protected function createDynamicCron(AbstractModel|AutomatedExportModel $object)
    {
        $this->createDynamicCronService->execute($object);
    }

    /**
     * @param AbstractModel|AutomatedExportModel $object
     * @return AutomatedExport
     */
    protected function _afterLoad(AbstractModel|AutomatedExportModel $object): AutomatedExport
    {
        /** @var AutomatedExportLinkInterface $automatedExportLink */

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

        if ($object->getId()) {
            $exportTypes = $object->getExportTypes();
            if (!is_array($exportTypes) && $exportTypes) {
                $object->setExportTypes(explode(',', $exportTypes));
            }

            $fileTypes = $object->getFileTypes();
            if (!is_array($fileTypes) && $fileTypes) {
                $object->setFileTypes(explode(',', $fileTypes));
            }
            $object->setRemotePassword($this->encryptor->decrypt($object->getRemotePassword()));
        }

        return parent::_afterLoad($object);
    }
}

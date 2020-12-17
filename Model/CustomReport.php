<?php
namespace DEG\CustomReports\Model;

use Magento\Framework\App\ObjectManager;

class CustomReport extends \Magento\Framework\Model\AbstractModel implements \DEG\CustomReports\Api\Data\CustomReportInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'deg_customreports_customreport';

    /**
     * @var GenericReportCollectionFactory
     */
    private $genericReportCollectionFactory;

    /**
     * CustomReport constructor.
     *
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param \DEG\CustomReports\Model\GenericReportCollectionFactory|null $genericReportCollectionFactory
     * @param array                                                        $data
     */
    public function __construct(\Magento\Framework\Model\Context $context,
                                \Magento\Framework\Registry $registry,
                                \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
                                \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
                                GenericReportCollectionFactory $genericReportCollectionFactory = null,
                                array $data = [])
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->genericReportCollectionFactory = $genericReportCollectionFactory ?: ObjectManager::getInstance()->get(GenericReportCollectionFactory::class);
    }

    protected function _construct()
    {
        $this->_init('DEG\CustomReports\Model\ResourceModel\CustomReport');
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return GenericReportCollection
     */
    public function getGenericReportCollection()
    {
        /** @var $genericReportCollection \DEG\CustomReports\Model\GenericReportCollection */

        $genericReportCollection = $this->genericReportCollectionFactory->create();
        $genericReportCollection->getSelect()->from(new \Zend_Db_Expr('(' . $this->getData('report_sql') . ')'));

        return $genericReportCollection;
    }
}

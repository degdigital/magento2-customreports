<?php declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Zend_Db_Expr;

/**
 * @method int getId()
 * @method string getReportName()
 * @method string getReportSql()
 * @method CustomReport setId(int $id)
 * @method CustomReport setReportName(string $reportName)
 * @method CustomReport setReportSql(string $reportSql)
 */
class CustomReport extends AbstractModel implements CustomReportInterface, IdentityInterface
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
     * @param \DEG\CustomReports\Model\GenericReportCollectionFactory|null $genericReportCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        GenericReportCollectionFactory $genericReportCollectionFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->genericReportCollectionFactory = $genericReportCollectionFactory;
    }

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    /**
     * @return GenericReportCollection
     */
    public function getGenericReportCollection(): GenericReportCollection
    {
        $genericReportCollection = $this->genericReportCollectionFactory->create();
        $formattedSql = $this->formatSql($this->getData('report_sql'));
        $genericReportCollection->getSelect()->from(new Zend_Db_Expr('('.$formattedSql.')'));

        return $genericReportCollection;
    }

    protected function _construct()
    {
        $this->_init(ResourceModel\CustomReport::class);
    }

    /**
     * @param string $rawSql
     *
     * @return string
     */
    protected function formatSql(string $rawSql): string
    {
        return trim($rawSql, ';');
    }
}

<?php declare(strict_types=1);

namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Model\CustomReport;
use DEG\CustomReports\Model\CustomReportFactory;
use DEG\CustomReports\Registry\CurrentCustomReport;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface as Logger;

class Builder
{
    /**
     * @var CustomReportFactory
     */
    private $customReportFactory;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Framework\Registry
     */
    private $currentCustomReportRegistry;
    /**
     * @var \DEG\CustomReports\Api\CustomReportRepositoryInterface
     */
    private $customReportRepository;
    /**
     * @param \DEG\CustomReports\Model\CustomReportFactory           $customReportFactory
     * @param \Psr\Log\LoggerInterface                               $logger
     * @param \DEG\CustomReports\Registry\CurrentCustomReport        $currentCustomReportRegistry
     * @param \DEG\CustomReports\Api\CustomReportRepositoryInterface $customReportRepository
     */
    public function __construct(
        CustomReportFactory $customReportFactory,
        Logger $logger,
        CurrentCustomReport $currentCustomReportRegistry,
        CustomReportRepositoryInterface $customReportRepository
    ) {
        $this->customReportFactory = $customReportFactory;
        $this->logger = $logger;
        $this->currentCustomReportRegistry = $currentCustomReportRegistry;
        $this->customReportRepository = $customReportRepository;
    }

    /**
     * Build custom report based on user request
     *
     * @param RequestInterface $request
     *
     * @return \DEG\CustomReports\Model\CustomReport
     */
    public function build(RequestInterface $request): CustomReport
    {
        $customReportId = (int)$request->getParam('customreport_id');
        $customReport = $this->customReportFactory->create();
        if ($customReportId) {
            try {
                $customReport = $this->customReportRepository->getById($customReportId);
            } catch (NoSuchEntityException $e) {
                $this->logger->critical($e);
            }
        }

        $this->currentCustomReportRegistry->set($customReport);

        return $customReport;
    }
}

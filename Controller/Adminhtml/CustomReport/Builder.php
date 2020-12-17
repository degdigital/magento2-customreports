<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace DEG\CustomReports\Controller\Adminhtml\CustomReport;

use DEG\CustomReports\Model\CustomReportFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
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
    private $registry;

    /**
     * @param CustomReportFactory $customReportFactory
     * @param Logger $logger
     * @param Registry $registry
     */
    public function __construct(
        CustomReportFactory $customReportFactory,
        Logger $logger,
        Registry $registry
    ) {
        $this->customReportFactory = $customReportFactory;
        $this->logger = $logger;
        $this->registry = $registry;
    }

    /**
     * Build custom report based on user request
     *
     * @param RequestInterface $request
     * @return \DEG\CustomReports\Model\CustomReport
     */
    public function build(RequestInterface $request)
    {
        $customReportId = (int)$request->getParam('customreport_id');
        /** @var $customReport \DEG\CustomReports\Model\CustomReport */
        $customReport = $this->customReportFactory->create();

        if ($customReportId) {
            try {
                $customReport->load($customReportId);
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        }

        $this->registry->register('current_customreport', $customReport);
        return $customReport;
    }
}

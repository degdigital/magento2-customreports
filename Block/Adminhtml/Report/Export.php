<?php declare(strict_types=1);
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile
namespace DEG\CustomReports\Block\Adminhtml\Report;

use DEG\CustomReports\Api\Data\CustomReportInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Convert\Excel;
use Magento\Framework\Data\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Export extends \Magento\Backend\Block\Widget\Grid\Export
{
    private $timeZone;

    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        TimezoneInterface $timeZone,
        array $data = []
    ) {
        parent::__construct($context, $collectionFactory, $data);
        $this->timeZone = $timeZone;
    }

    /**
     * @return $this|\DEG\CustomReports\Block\Adminhtml\Report\Export
     */
    public function _prepareLayout(): Export
    {
        return $this;
    }

    /**
     * Prepare export button
     * This had to be implemented as a lazy prepare because if the export block is not added
     * through the layout, there is no way for the _prepareLayout to work since the parent block
     * would not be set yet.
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function lazyPrepareLayout(): Export
    {
        $this->setChild(
            'export_button',
            $this->getLayout()->createBlock(
                'Magento\Backend\Block\Widget\Button'
            )->setData(
                [
                    'label' => __('Export'),
                    'onclick' => $this->getParentBlock()->getJsObjectName().'.doExport()',
                    'class' => 'task',
                ]
            )
        );

        return parent::_prepareLayout();
    }

    /**
     * Retrieve a file container array by grid data as MS Excel 2003 XML Document
     * Return array with keys type and value
     *
     * @param string $sheetName
     *
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getExcelFile($sheetName = ''): array
    {
        $collection = $this->_getPreparedCollection();

        $convert = new Excel($collection->getIterator(), [$this, 'getRowRecord']);

        $name = md5(microtime());
        $file = $this->_path.'/'.$name.'.xml';

        $this->_directory->create($this->_path);
        $stream = $this->_directory->openFile($file, 'w+');
        $stream->lock();

        $convert->setDataHeader($this->_getExportHeaders());
        if ($this->getCountTotals()) {
            $convert->setDataFooter($this->_getExportTotals());
        }

        $convert->write($stream, $sheetName);
        $stream->unlock();
        $stream->close();

        return [
            'type' => 'filename',
            'value' => $file,
            'rm' => true  // can delete file after use
        ];
    }

    /**
     * @param \DEG\CustomReports\Api\Data\CustomReportInterface $customReport
     * @param                                                   $automatedExport
     *
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getCronCsvFile(CustomReportInterface $customReport, $automatedExport)
    {
        $string = $automatedExport->getFilenamePattern();
        $name = $this->replaceVariables($string, $customReport, $automatedExport);

        $file = $this->_path.'/'.$name.'.csv';

        $this->_directory->create($this->_path);
        $stream = $this->_directory->openFile($file, 'w+');

        $stream->writeCsv($this->_getExportHeaders());
        $stream->lock();
        $this->_exportIterateCollection('_exportCsvItem', [$stream]);
        if ($this->getCountTotals()) {
            $stream->writeCsv($this->_getExportTotals());
        }
        $stream->unlock();
        $stream->close();

        return [
            'type' => 'filename',
            'value' => $file,
            'rm' => false  // can delete file after use
        ];
    }

    protected function replaceVariables($string, $customReport, $automatedExport)
    {
        $formattedReportName = strtolower(str_replace(' ', '_', $customReport->getReportName()));

        $replaceableVariables = [
            '%d%' => $this->timeZone->date()->format('d'),
            '%m%' => $this->timeZone->date()->format('m'),
            '%y%' => $this->timeZone->date()->format('y'),
            '%Y%' => $this->timeZone->date()->format('Y'),
            '%h%' => $this->timeZone->date()->format('H'),
            '%i%' => $this->timeZone->date()->format('i'),
            '%s%' => $this->timeZone->date()->format('s'),
            '%W%' => $this->timeZone->date()->format('W'),
            '%reportname%' => $formattedReportName,
        ];
        $string = str_replace(array_keys($replaceableVariables), array_values($replaceableVariables), $string);

        return $string;
    }
}

<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class FileTypes implements OptionSourceInterface
{
    public const EXTENSION_CSV = 'csv';
    public const EXTENSION_TSV = 'tsv';
    public const EXTENSION_TXT_PIPE = 'txt' . self::EXTENSION_METADATA_SEPARATOR . 'pipe'; # txt:pipe
    public const EXTENSION_XML_EXCEL = 'xml' . self::EXTENSION_METADATA_SEPARATOR . 'excel'; # xml:excel
    public const EXTENSION_METADATA_SEPARATOR = ':';
    public const LABEL_CSV = 'CSV';
    public const LABEL_TSV = 'TSV';
    public const LABEL_TXT_PIPE_DELIMITED = 'TXT (Pipe-delimited)';
    public const LABEL_EXCEL_XML = 'Excel XML';

    /**
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => static::EXTENSION_CSV, 'label' => __(static::LABEL_CSV)],
            ['value' => static::EXTENSION_TSV, 'label' => __(static::LABEL_TSV)],
            ['value' => static::EXTENSION_TXT_PIPE, 'label' => __(static::LABEL_TXT_PIPE_DELIMITED)],
            ['value' => static::EXTENSION_XML_EXCEL, 'label' => __(static::LABEL_EXCEL_XML)],
        ];
    }
}

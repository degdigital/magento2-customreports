<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class FileTypes implements OptionSourceInterface
{
    public const EXTENSION_CSV = 'csv';
    public const EXTENSION_TSV = 'tsv';
    public const EXTENSION_TXT_PIPE = 'txt' . self::EXTENSION_METADATA_SEPARATOR . 'pipe'; # txt/pipe
    public const EXTENSION_METADATA_SEPARATOR = '/';

    /**
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => static::EXTENSION_CSV, 'label' => __('CSV')],
            ['value' => static::EXTENSION_TSV, 'label' => __('TSV')],
            ['value' => static::EXTENSION_TXT_PIPE, 'label' => __('TXT (Pipe-delimited)')],
        ];
    }
}

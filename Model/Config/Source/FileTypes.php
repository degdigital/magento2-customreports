<?php
declare(strict_types=1);

namespace DEG\CustomReports\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class FileTypes implements OptionSourceInterface
{
    public const EXTENSION_CSV = 'csv';

    /**
     * @todo: add support for other file types
     *
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => static::EXTENSION_CSV, 'label' => __('CSV')],
        ];
    }
}

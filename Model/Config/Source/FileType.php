<?php
declare(strict_types=1);

namespace DEG\CustomReports\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class FileType implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'csv', 'label' => __('CSV')],
        ];
    }
}

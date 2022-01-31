<?php
declare(strict_types=1);

namespace DEG\CustomReports\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ExportTypes implements OptionSourceInterface
{
    public const LOCAL_FILE_DROP = 'local_file_drop';
    public const REMOTE_FILE_DROP = 'remote_file_drop';

    public function toOptionArray(): array
    {
        return [
            ['value' => static::LOCAL_FILE_DROP, 'label' => __('Local File Drop')],
            ['value' => static::REMOTE_FILE_DROP, 'label' => __('Remote File Drop')],
        ];
    }
}

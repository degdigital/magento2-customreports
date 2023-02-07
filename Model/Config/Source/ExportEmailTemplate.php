<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\Config\Source;

use Magento\Config\Model\Config\Source\Email\Template;
use Magento\Framework\Data\OptionSourceInterface;

class ExportEmailTemplate implements OptionSourceInterface
{
    public function __construct(
        protected Template $emailTemplateSource
    ) {
    }

    public function toOptionArray()
    {
        return $this->emailTemplateSource
            ->setPath('deg_customreports_automated_exports_default_export_email_template')
            ->toOptionArray();
    }
}

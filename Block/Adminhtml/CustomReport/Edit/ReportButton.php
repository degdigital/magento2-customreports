<?php declare(strict_types=1);

namespace DEG\CustomReports\Block\Adminhtml\CustomReport\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ReportButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Show Report'),
            'on_click' => sprintf("location.href = '%s';", $this->getReportUrl()),
            'class' => 'action-secondary',
            'sort_order' => 40,
        ];
    }
}

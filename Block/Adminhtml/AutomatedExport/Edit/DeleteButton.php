<?php declare(strict_types=1);

namespace DEG\CustomReports\Block\Adminhtml\AutomatedExport\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        if (!$this->getObjectId()) {
            return [];
        }

        return [
            'label' => __('Delete Report'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm( \''.__(
                'Are you sure you want to do this?'
            ).'\', \''.$this->getDeleteUrl().'\')',
            'sort_order' => 20,
        ];
    }
}

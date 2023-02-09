<?php declare(strict_types=1);

namespace DEG\CustomReports\Block\Adminhtml\Report;

use Magento\Framework\Exception\LocalizedException;

class Export extends \Magento\Backend\Block\Widget\Grid\Export
{
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
     * @throws LocalizedException
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
}

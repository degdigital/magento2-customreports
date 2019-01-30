<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile
namespace DEG\CustomReports\Block\Adminhtml\Report;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Export extends \Magento\Backend\Block\Widget\Grid\Export
{
    public function _prepareLayout()
    {
        return $this;
    }
    /**
     * Prepare export button
     *
     * This had to be implemented as a lazy prepare because if the export block is not added
     * through the layout, there is no way for the _prepareLayout to work since the parent block
     * would not be set yet.
     *
     * @return $this
     */
    public function lazyPrepareLayout()
    {
        $this->setChild(
            'export_button',
            $this->getLayout()->createBlock(
                'Magento\Backend\Block\Widget\Button'
            )->setData(
                [
                    'label' => __('Export'),
                    'onclick' => $this->getParentBlock()->getJsObjectName() . '.doExport()',
                    'class' => 'task',
                ]
            )
        );
        return parent::_prepareLayout();
    }
}

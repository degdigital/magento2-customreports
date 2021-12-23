<?php

namespace DEG\CustomReports\Test\Unit\Block\Adminhtml\AutomatedExport\Edit;

use PHPUnit\Framework\TestCase;

class SaveButtonTest extends TestCase
{
    protected $block;

    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->block = $objectManager->getObject(
            'DEG\CustomReports\Block\Adminhtml\AutomatedExport\Edit\SaveButton'
        );
    }

    public function testGetButtonData(): void
    {
        $this->assertEquals($this->block->getButtonData(), [
            'label' => __('Save Report'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ]);
    }
}

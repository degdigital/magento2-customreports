<?php

namespace DEG\CustomReports\Test\Unit\Block\Adminhtml\AutomatedExport\Edit;

use PHPUnit\Framework\TestCase;

class ResetButtonTest extends TestCase
{

    protected $block;

    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->block = $objectManager->getObject(
            'DEG\CustomReports\Block\Adminhtml\AutomatedExport\Edit\ResetButton'
        );
    }

    public function testGetButtonData(): void
    {
        $this->assertEquals($this->block->getButtonData(), [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30
        ]);
    }
}

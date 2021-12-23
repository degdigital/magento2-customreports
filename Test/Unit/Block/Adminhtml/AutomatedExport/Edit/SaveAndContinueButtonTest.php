<?php

namespace DEG\CustomReports\Test\Unit\Block\Adminhtml\AutomatedExport\Edit;

use PHPUnit\Framework\TestCase;

class SaveAndContinueButtonTest extends TestCase
{
    protected $block;

    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->block = $objectManager->getObject(
            'DEG\CustomReports\Block\Adminhtml\AutomatedExport\Edit\SaveAndContinueButton'
        );
    }

    public function testGetButtonData(): void
    {
        $this->assertEquals($this->block->getButtonData(), [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order' => 80,
        ]);
    }
}

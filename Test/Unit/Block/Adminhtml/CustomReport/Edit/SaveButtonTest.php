<?php
declare(strict_types=1);

namespace DEG\CustomReports\Test\Unit\Block\Adminhtml\CustomReport\Edit;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class SaveButtonTest extends TestCase
{
    protected object $block;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->block = $objectManager->getObject(
            'DEG\CustomReports\Block\Adminhtml\CustomReport\Edit\SaveButton'
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

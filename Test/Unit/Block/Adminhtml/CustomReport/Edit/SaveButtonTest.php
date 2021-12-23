<?php
/**
 * Test th general Gigya Script load block.
 * loads Gigya script on each page load, with various parameters
 */
namespace DEG\CustomReports\Test\Unit\Block\Adminhtml\CustomReport\Edit;

use PHPUnit\Framework\TestCase;

class SaveButtonTest extends TestCase
{

    protected $block;
    /**
     * Mock scopeConfig to tests with different System configuration values
     */
    protected function setUp(): void
    {
        // create the tested object, with the mocked config class
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->block = $objectManager->getObject(
            'DEG\CustomReports\Block\Adminhtml\CustomReport\Edit\SaveButton'
        );
    }

    /**
     * Test language settings:
     * Normal behavior - language is set to "en_US" -> getLanguage returns "en_US"
     */
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

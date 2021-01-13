<?php declare(strict_types=1);

namespace DEG\CustomReports\Block\Adminhtml\AutomatedExport\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{
    //putting all the button methods in here.  No "right", but the whole
    //button/GenericButton thing seems -- not that great -- to begin with
    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    private $context;

    /**
     * GenericButton constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->getUrl('*/*/listing');
    }

    /**
     * @return string
     */
    public function getDeleteUrl(): string
    {
        return $this->getUrl('*/*/delete', ['object_id' => $this->getObjectId()]);
    }

    /**
     * @param string $route
     * @param array  $params
     *
     * @return string
     */
    public function getUrl($route = '', $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    /**
     * @return mixed
     */
    public function getObjectId()
    {
        return $this->context->getRequest()->getParam('automatedexport_id');
    }
}

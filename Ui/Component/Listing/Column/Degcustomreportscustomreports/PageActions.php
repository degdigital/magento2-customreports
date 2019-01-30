<?php

namespace DEG\CustomReports\Ui\Component\Listing\Column\Degcustomreportscustomreports;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class PageActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    private $authorization;

    /**
     * PageActions constructor.
     * @param ContextInterface                          $context
     * @param UiComponentFactory                        $uiComponentFactory
     * @param \Magento\Framework\AuthorizationInterface $authorization
     * @param array                                     $components
     * @param array                                     $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\AuthorizationInterface $authorization,
        array $components = [],
        array $data = [])
    {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
        $this->authorization = $authorization;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as &$item) {
                $name = $this->getData("name");
                $id   = $item["customreport_id"] ?? "X";
                if ($this->authorization->isAllowed('DEG_CustomReports::customreports_edit')) {
                    $item[$name]["view"] = [
                        "href"  => $this->getContext()->getUrl(
                            "deg_customreports_customreports/customreport/edit", ["customreport_id" => $id]),
                        "label" => __("Edit"),
                    ];
                }
                if ($this->authorization->isAllowed('DEG_CustomReports::customreports_view_report')) {
                    $item[$name]["report"] = [
                        "href"  => $this->getContext()->getUrl(
                            "deg_customreports_customreports/customreport/report", ["customreport_id" => $id]),
                        "label" => __("Report"),
                    ];
                }
            }
        }

        return $dataSource;
    }
}

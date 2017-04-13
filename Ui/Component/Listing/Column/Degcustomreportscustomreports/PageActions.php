<?php
namespace DEG\CustomReports\Ui\Component\Listing\Column\Degcustomreportscustomreports;

class PageActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                $id = "X";
                if(isset($item["customreport_id"]))
                {
                    $id = $item["customreport_id"];
                }
                $item[$name]["view"] = [
                    "href"=>$this->getContext()->getUrl(
                        "deg_customreports_customreports/customreport/edit",["customreport_id"=>$id]),
                    "label"=>__("Edit")
                ];
                $item[$name]["report"] = [
                    "href"=>$this->getContext()->getUrl(
                        "deg_customreports_customreports/customreport/report",["customreport_id"=>$id]),
                    "label"=>__("Report")
                ];
            }
        }

        return $dataSource;
    }    
    
}

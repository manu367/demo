<?php
class BarChartModal{}
class LineChartModal{}
class PieChartModal {

    private $title;
    private $data = [];

    public function __construct($title) {
        $this->title = $title;
    }

    public function addPoint($name, $value) {
        $this->data[] = [
            "name" => $name,
            "y" => (float)$value
        ];
    }

    public function getData() {
        return [
            [
                "name" => $this->title,
                "colorByPoint" => true,
                "data" => $this->data
            ]
        ];
    }

    public function getJson() {
        return json_encode($this->getData());
    }
}
class AreaChartModal{}
class ScatterChartModal{}
class GeoChartModal{}
class FunnelChartModal{}
class CandlestickChartModal{}
class GaugeChartModal{}
class ChoroplethChartModal{}
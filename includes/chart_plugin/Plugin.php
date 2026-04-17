<?php

class PluginDataExpose {

    private $charttype, $title,$subtitle, $align;
    private $xAxis, $yAxis;
    private $series = [];
    public function setCharttype($charttype) {
        $this->charttype = $charttype;
    }
    public function setTitle($title, $align = 'center') {
        $this->title = ['text' => $title, 'align' => $align];
    }
    public function setSubtitle($text) {
        $this->subtitle = ['text' => $text];
    }
    public function setYAxis($text) {
        $this->yAxis = [
            'title' => ['text' => $text]
        ];
    }
    public function setXAxis($range = '') {
        $this->xAxis = [
            'accessibility' => [
                'rangeDescription' => $range
            ]
        ];
    }

    public function addSeries($name, $data) {
        $this->series[] = [
            'name' => $name,
            'data' => $data
        ];
    }

    public function getChartConfig() {
        return [
            'title' => $this->title,
            'yAxis' => $this->yAxis,
            'xAxis' => $this->xAxis,
            'series' => $this->series
        ];
    }

    /**
     * <script>
     * const chartConfig = <?php echo $config; ?>;
     *
     * Highcharts.chart('container', chartConfig);
     * </script>
     */
    public function toJson() {
        return json_encode($this->getChartConfig());
    }
}

function my_plugin_function() {
    return ['data'=>[1,2,3,4,5,6],'msg'=>'"Hello from chart_plugin!"'];
}

//################# fms_master #############
function getFMsMaste_Data($link1, $date_wise_chart_data){

    $dates = explode(" - ", $date_wise_chart_data);

    $start_date = date("Y-m-d 00:00:00", strtotime($dates[0]));
    $end_date   = date("Y-m-d 23:59:59", strtotime($dates[1]));

    $sql = "SELECT category, status, COUNT(*) as total 
            FROM fms_master 
            WHERE created_at BETWEEN '$start_date' AND '$end_date'
            GROUP BY category, status";

    $result = mysqli_query($link1, $sql);

    $data = [];
    while($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }
    return $data;
}
function getFormMaster_data($link1,$date_wise_chart_data){
    $dates = explode(" - ", $date_wise_chart_data);

    $start_date = date("Y-m-d 00:00:00", strtotime($dates[0]));
    $end_date   = date("Y-m-d 23:59:59", strtotime($dates[1]));

    $sql = "SELECT * FROM form_master WHERE created_date BETWEEN '$start_date' AND '$end_date'";

    $result = mysqli_query($link1, $sql);

    $data = [];
    while($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }
    return $data;
}
function prepareChartData($data) {
    $categories = [];
    $statusData = [];

    foreach ($data as $row) {
        $cat = $row['category'];
        $status = $row['status'];
        $total = (int)$row['total'];

        if (!in_array($cat, $categories)) {
            $categories[] = $cat;
        }

        $statusData[$status][$cat] = $total;
    }

    // build series
    $series = [];
    foreach ($statusData as $status => $values) {
        $dataArr = [];

        foreach ($categories as $cat) {
            $dataArr[] = $values[$cat] ?? 0;
        }

        $series[] = [
            "name" => $status,
            "data" => $dataArr
        ];
    }

    return [
        "categories" => $categories,
        "series" => $series
    ];
}
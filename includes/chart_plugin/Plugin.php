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

//####################################### admin_users ############################
function activeUser_expose(){}
function statebyUser_expose(){}
function cityByUsers_expose(){}
function desitationsByUser_expose(){}


//####################################### Dropdown_master ############################
function getAllActiveDropDowneChart_expose(){}


//####################################### role_master ############################
function activeRole_expose(){}


//###################################### FMS_Master #############################
function activeFmsMaster_expose(){}
function activeFMSwithSteps_and_totalform_expose(){}
function fms_with_table_name_expose(){}

// ############################### Form Master ##################################
function active_formMaster_expose(){}
function from_seq_expose(){}
function formDataWise_expose(){}

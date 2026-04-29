<?php
require_once("../includes/config.php");
global $link1;
class ChartModal{
    private $charttype,$title,$subtitle,$x_axis_label,$y_axis_label;
    private $x_axis_param,$y_axis_param;
    private $from_date,$to_date,$alignment;
    private $operations,$operation_method,$table_name,$fms_id,$update_by,$update_ip;

    public function __construct($charttype, $title, $subtitle, $x_axis_label, $y_axis_label, $x_axis_param, $y_axis_param, $from_date, $to_date, $alignment, $operations, $operation_method, $table_name, $fms_id, $update_by, $update_ip)
    {
        $this->charttype = $charttype;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->x_axis_label = $x_axis_label;
        $this->y_axis_label = $y_axis_label;
        $this->x_axis_param = $x_axis_param;
        $this->y_axis_param = $y_axis_param;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->alignment = $alignment;
        $this->operations = $operations;
        $this->operation_method = $operation_method;
        $this->table_name = $table_name;
        $this->fms_id = $fms_id;
        $this->update_by = $update_by;
        $this->update_ip = $update_ip;
    }

    public function getCharttype()
    {
        return $this->charttype;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function getXAxisLabel()
    {
        return $this->x_axis_label;
    }

    public function getYAxisLabel()
    {
        return $this->y_axis_label;
    }

    public function getXAxisParam()
    {
        return $this->x_axis_param;
    }

    public function getYAxisParam()
    {
        return $this->y_axis_param;
    }

    public function getFromDate()
    {
        return $this->from_date;
    }

    public function getToDate()
    {
        return $this->to_date;
    }

    public function getAlignment()
    {
        return $this->alignment;
    }

    public function getOperations()
    {
        return $this->operations;
    }


    public function getOperationMethod()
    {
        return $this->operation_method;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    
    public function getFmsId()
    {
        return $this->fms_id;
    }
    
    public function getUpdateBy()
    {
        return $this->update_by;
    }
    
    public function getUpdateIp()
    {
        return $this->update_ip;
    }
    
}
set_exception_handler(function($e){
    if($e instanceof GlobalException){
        $errormsg=$e->getMessage();
        header("Location: gui_master.php?{$errormsg}");
        exit;
    }
});
function loadChartOperations_Gui($link1, $operationsId) {

    $sql = "SELECT operation FROM chart_operation WHERE id = $operationsId AND status = 1";
    $result = mysqli_query($link1, $sql);
    if (!$result) {
        throw new Exception(mysqli_error($link1));
    }
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['operation'];
    }
    return null;
}

$response=[];
$response['pid']=$_REQUEST['pid'];
$response['hid']=$_REQUEST['hid'];
$response['id']=$_REQUEST['id'];
if(isset($_REQUEST['id']) && $_REQUEST['id']!==""){
    $flag=true;
    $data=getFMsbyid($link1,$_REQUEST['id']);
}
$hide=false;
$msg=null;

$tablename=$data['table_name'];
if(isset($_POST['submit'])){
    $charttyle=$_POST['charttype'];
    $charttitle=$_POST['charttitle'];
    $chartsubtitle=$_POST['chartsubtitle'];
    $x_axis_label=$_POST['x_axis_label'];
    $y_axis_label=$_POST['y_axis_label'];

    $x_axis_param=$_POST['x_axis_param'];
    $y_axis_param=$_POST['y_axis_param'];
    $group_operations=$_POST['group_operations'];
    $chart_alignment=$_POST['chart_alignment'];

    $create_method=$_REQUEST['charttype'].'_'.loadChartOperations_Gui($link1,$_REQUEST['group_operations']);
    $create_method=$create_method.'_Chart';
    $modal=new ChartModal($charttyle,$charttitle,$chartsubtitle,
            $x_axis_label,$y_axis_label,
            $x_axis_param,$y_axis_param,$_REQUEST['from_date'], $_REQUEST['to_date'],
            $chart_alignment,$group_operations,$create_method,$tablename,
            $data['id'],$_SESSION['userid'],$_SERVER['REMOTE_ADDR']
    );

    $chart_type = mysqli_real_escape_string($link1, $modal->getCharttype());
    $title = mysqli_real_escape_string($link1, $modal->getTitle());
    $subtitle = mysqli_real_escape_string($link1, $modal->getSubtitle());
    $x_axis = mysqli_real_escape_string($link1, $modal->getXAxisLabel());
    $y_axis = mysqli_real_escape_string($link1, $modal->getYAxisLabel());
    $x_axis_param1 = mysqli_real_escape_string($link1, $modal->getXAxisParam());
    $x_axis_param2 = mysqli_real_escape_string($link1, $modal->getYAxisParam());
    $from_date = mysqli_real_escape_string($link1, $modal->getFromDate());
    $to_date = mysqli_real_escape_string($link1, $modal->getToDate());
    $alignment = mysqli_real_escape_string($link1, $modal->getAlignment());
    $operations = mysqli_real_escape_string($link1, $modal->getOperations());
    $operation_method = mysqli_real_escape_string($link1, $modal->getOperationMethod());
    $table_name = mysqli_real_escape_string($link1, $modal->getTableName());
    $fms_id = mysqli_real_escape_string($link1, $modal->getFmsId());
    $updated_by = mysqli_real_escape_string($link1, $modal->getUpdateBy());
    $updated_ip = mysqli_real_escape_string($link1, $modal->getUpdateIp());
    $sql = "
INSERT INTO dashboard_master (
    chart_type,title,subtitle,x_axis,y_axis,x_axis_param1,x_axis_param2,
    from_date,to_date,operations,operation_method,alignment,table_name,fms_id,remarks,chart_status,updated_by,updated_ip,created_at,updated_at
) VALUES (
          '$chart_type','$title','$subtitle','$x_axis','$y_axis','$x_axis_param1','$x_axis_param2',
          '$from_date','$to_date','$operations','$operation_method','$alignment','$table_name',
          '$fms_id','', 1,'$updated_by','$updated_ip',NOW(),NOW())";
    $result = mysqli_query($link1, $sql);
    if ($result) {
        $hide=true;
        $msg="Changes saved successfully";
    }
    else {
        $hide=false;
        $msg=mysqli_error($link1);
    }


}
if(isset($_POST['preview_button'])){
    $x_axis_param=$_POST['x_axis_param'];
    $y_axis_param=$_POST['y_axis_param'];
    $create_method=$_REQUEST['charttype'].'_'.loadChartOperations_Gui($link1,$_REQUEST['group_operations']);
    $create_method=$create_method.'_Chart';
    $report_data=[];
    $report_data['fms_id']=$_REQUEST['fms_id'];
    $report_data['from_date']=$_POST['from_date'];
    $report_data['to_date']=$_POST['to_date'];
    $report_data['x_axis_param']=$_POST['x_axis_param'];
    $report_data['y_axis_param']=$_POST['y_axis_param'];
    $data_create_method=dynamicBinding($create_method,$link1,$report_data);
    var_dump($data_create_method);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Responsive Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/funnel.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/stock/12.6.0/highstock.js"></script>

    <script src="../js/chartobserver.js"></script>
    <style>
        /* Smooth scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(100,100,100,0.4);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(100,100,100,0.7);
        }
    </style>
</head>
<body class="h-screen overflow-hidden bg-gray-100">

<!-- Mobile Header -->
<div class="md:hidden flex items-center justify-between p-4 bg-white shadow">
    <h1 class="text-lg font-semibold">App</h1>
    <button id="menuBtn" class="text-2xl">⋮</button>
</div>

<!-- Mobile Sidebar Overlay -->
<div id="mobileMenu" class="fixed inset-0 bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300 z-50">
    <div id="sidebarPanel" class="w-64 bg-white h-full p-4 transform -translate-x-full transition-transform duration-300 ease-in-out shadow-xl">
        <button id="closeBtn" class="mb-4 text-xl">✕</button>
        <ul class="space-y-3">
            <li class="p-2 bg-gray-200 rounded">Dashboard</li>
            <li class="p-2 bg-gray-200 rounded">Profile</li>
            <li class="p-2 bg-gray-200 rounded">Settings</li>
        </ul>
    </div>
</div>

<!-- Main Layout -->
<div class="flex h-full">

    <!-- Main Content -->
    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto p-4 md:p-6">
        <h2 class="text-2xl font-bold mb-6"><?=$data['fmsname']?> Chart Panel</h2>
        <?php
        if($msg){
            ?>
            <div class="mb-4 flex items-center justify-between rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                <span>Changes saved successfully.</span>

                <a href="fms_charts.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>&id=<?=$_REQUEST['id']??''?>"
                   class="ml-4 px-3 py-1.5 rounded-md bg-green-600 text-white text-sm hover:bg-green-700 transition">
                    ← Back
                </a>
            </div>
        <?php } ?>
        <!-- Chart Card -->
        <div class="bg-white rounded-2xl shadow-md p-4 md:p-6 w-full max-w-5xl mx-auto">
            <div id="container" class="w-full h-[400px] md:h-[500px]"></div>
        </div>
    </div>

    <!-- Sidebar (Desktop) -->
    <aside class="hidden md:block w-80 bg-gray-800 h-full overflow-visible overflow-y-auto p-6 text-white shadow-xl">

        <h2 class="text-xl font-semibold mb-6 border-b border-gray-600 pb-2">
            Chart Config Panel
        </h2>
        <form action="" method="post" id="form1">
            <input type="hidden" name="fms_id" value="<?=$data['id']?>">
            <div class="space-y-5">

                <!-- Chart Type -->
                <div>
                    <?php
                    $selected_chart = $charttyle ?? '';
                    $disableSelect = !empty($selected_chart) ? 'disabled' : '';
                    ?>
                    <label class="block text-sm mb-1 text-gray-300">Chart Type</label>
                    <select style="text-transform: capitalize" <?=$disableSelect?> id="charttype" name="charttype"  required class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>-- Select Chart Type --</option>
                        <?php
                        $sql = "SELECT * FROM charts_master WHERE status='1'";
                        $result = mysqli_query($link1, $sql);
                        while($row = mysqli_fetch_assoc($result)) {
                            $selected = ($selected_chart == $row['chart_type']) ? 'selected' : '';
                            $isDisabled = ($selected_chart == $row['chart_type']) ? 'disabled' : '';

                            echo "<option value='".$row['chart_type']."' $selected>".$row['chart_name']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Chart Title -->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Chart Title</label>
                    <input type="text" id="charttitle" name="charttitle" value="<?=$charttitle??''?>" <?=$charttitle?'disabled':''?>
                           placeholder="Enter chart title"
                           class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <!--                chart subtitile-->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Chart Subtitle</label>
                    <input type="text" id="chartsubtitle" name="chartsubtitle" value="<?=$chartsubtitle??''?>" <?=$chartsubtitle?'disabled':''?> placeholder="Subtitle of Chart"
                           class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!--                x-axis label name-->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">X-Axis Label</label>
                    <input type="text" id="x_axis_label" name="x_axis_label" value="<?=$x_axis_label??''?>" <?=$x_axis_label?'disabled':''?> placeholder="X-axis Name"
                           class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>


                <!--                Y-axis name-->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Y-Axis Label</label>
                    <input type="text" id="y_axis_label" name="y_axis_label" value="<?=$y_axis_label??''?>" <?=$y_axis_label?'disabled':''?> placeholder="Y-axis Name"
                           class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Parameters -->
                <div>
                    <?php
                    $x_axis_param_selected = $_REQUEST['x_axis_param'] ?? '';
                    $x_axis_param_disabled=!empty($x_axis_param_selected)?'disabled':'';
                    ?>
                    <label class="block text-sm mb-1 text-gray-300">X-Axis Parameter</label>
                    <select <?=$x_axis_param_disabled?> style="text-transform: capitalize" id="x_axis_param" name="x_axis_param" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>-- Select Parameter --</option>
                        <?php
                        $tablename = mysqli_real_escape_string($link1, $data['table_name'] ?? '');
                        $exclude = ['id', 'created_date', 'update_date', 'updated_by', 'updated_ip'];
                        $sql = "SHOW COLUMNS FROM `$tablename`";
                        $result = mysqli_query($link1, $sql);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $col = $row['Field'];
                                if (!in_array($col, $exclude)) {
                                    $isSelected = ($x_axis_param_selected === $col) ? 'selected' : '';
                                    echo "<option value='$col' $isSelected>$col</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>

                <?php
                ?>
                <div>
                    <?php
                    $y_axis_param_selected = $_REQUEST['y_axis_param'] ?? '';
                    $y_axis_param_disabled=!empty($y_axis_param_selected)?'disabled':'';
                    ?>
                    <label class="block text-sm mb-1 text-gray-300">Y-Axis Parameter</label>
                    <select <?=$y_axis_param_disabled?> style="text-transform: capitalize" id="y_axis_param" name="y_axis_param" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>-- Select Parameter --</option>
                        <?php
                        $tablename = mysqli_real_escape_string($link1, $data['table_name'] ?? '');

                        $exclude = ['id', 'created_date', 'update_date', 'updated_by', 'updated_ip'];

                        $sql = "SHOW COLUMNS FROM `$tablename`";
                        $result = mysqli_query($link1, $sql);

                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $col = $row['Field'];

                                if (!in_array($col, $exclude)) {
                                    $isSelected = ($y_axis_param_selected === $col) ? 'selected' : '';
                                    echo "<option value='$col' $isSelected>$col</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>


                <div class="space-y-4">

                    <!-- Date Range Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- From Date -->
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">From Date</label>
                            <input type="date" id="from_date" name="from_date"
                                   class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- To Date -->
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">To Date</label>
                            <input type="date" id="to_date" name="to_date"
                                   class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                    </div>

                </div>
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Group Operations</label>
                    <select style="text-transform: capitalize" id="group_operations" name="group_operations" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>-- Select Parameter --</option>
                        <?php
                        if(isset($group_operations)){
                            echo "<option value='$group_operations' selected>$group_operations</option>";
                        }
                        ?>
                    </select>
                </div>


                <div style="display: none">
                    <label class="block text-sm mb-1 text-gray-300">Filter Month (2026) </label>
                    <select id="filter_month"
                            class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Months</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-600 pt-4 space-y-4">

                    <!-- Alignment -->
                    <div>
                        <label class="block text-sm mb-1 text-gray-300">Alignment</label>
                        <?php
                        $selected = $chart_alignment??'';
                        $select=!empty($selected)?'selected':'';
                        $isDisabled=!empty($selected)?'disabled':'';

                        ?>
                        <select <?=$isDisabled?> id="chart_alignment" name="chart_alignment" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Select Alignment --</option>
                            <option value="left" <?=$select?>>Left</option>
                            <option value="center" <?=$select?>>Center</option>
                            <option value="right" <?=$select?>>Right</option>
                        </select>
                    </div>

                    <!-- Color -->
                    <div>
                        <label class="block text-sm mb-1 text-gray-300">Theme Color</label>
                        <input id="chart_color" type="color"
                               class="w-full h-10 p-1 rounded-lg bg-gray-700 border border-gray-600 cursor-pointer">
                    </div>

                </div>

                <!-- Button -->
                <?php
                if(!$hide){
                    echo '
                        <div style="display: flex;flex-direction: row;justify-content: space-around;">
                        <button type="submit" id="preview_button" name="preview_button" class="mx-2 w-full mt-4 bg-blue-600 hover:bg-blue-700 transition rounded-lg py-2 font-medium">
                         Preview
                    </button>
                    <button type="submit" id="save" name="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 transition rounded-lg py-2 font-medium mx-2">
                         Save
                    </button>
</div>
                        ';
                }
                ?>

            </div>
        </form>

    </aside>

</div>

<script>
    const menuBtn = document.getElementById('menuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeBtn = document.getElementById('closeBtn');
    const sidebarPanel = document.getElementById('sidebarPanel');

    function openMenu() {
        mobileMenu.classList.remove('pointer-events-none');
        mobileMenu.classList.remove('opacity-0');
        sidebarPanel.classList.remove('-translate-x-full');
    }

    function closeMenu() {
        mobileMenu.classList.add('opacity-0');
        sidebarPanel.classList.add('-translate-x-full');
        setTimeout(() => {
            mobileMenu.classList.add('pointer-events-none');
        }, 300);
    }

    menuBtn.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', closeMenu);

    mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            closeMenu();
        }
    });
    document.getElementById("preview_button").addEventListener("click", (e) => {
        const type_of_chart_local = document.getElementById("charttype").value;
        const x_axis_param_local = document.getElementById("x_axis_param").value;
        const y_axis_param_local = document.getElementById("y_axis_param").value;
        const group_operation_local = document.getElementById("group_operations").value;

        if (
            !type_of_chart_local ||
            !x_axis_param_local ||
            !y_axis_param_local ||
            !group_operation_local
        ) {
            e.preventDefault();
            alert("Please select all fields before proceeding.");
            return;
        }
        // if everything is selected → form will proceed normally
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded",function (){
        console.log(Highcharts.charts);
        const wrapper=new DataWrapper();
        wrapper.charttype="<?=$_REQUEST['charttype']??''?>";
        wrapper.chartTitle="<?=$_REQUEST['charttitle']??''?>";
        wrapper.chartSubtitle="<?=$_REQUEST['chartsubtitle']??''?>";
        wrapper.xAxisLabel="<?=$_REQUEST['x_axis_label']??''?>";
        wrapper.yAxisLabel="<?=$_REQUEST['y_axis_label']??''?>"
        SingleTonInstanceHightChart.renderChart(Highcharts,wrapper);
    });
</script>
</body>
</html>
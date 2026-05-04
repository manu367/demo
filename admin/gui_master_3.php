<?php
$page_opteration="submit_button";
$meta_data=[];
require_once("../includes/config.php");
global $link1;


set_exception_handler(function($e){
    if($e instanceof GlobalException){
        $msg=$e->getMessage();
        header("location:gui_master_3.php?$msg");
        exit();
    }
    var_dump("sdsc",$e->getMessage());exit();
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


if(isset($_REQUEST['id']) && $_REQUEST['id']!==""){
    $flag=true;
    $data=getFMsbyid($link1,$_REQUEST['id']);
}

function saveChart($link,$data,$tablename,$fms_id,$updated_by,$updated_ip){
    if(!$link){
        throw new GlobalException(mysqli_error($link));
    }
    if(!$fms_id){
        throw new GlobalException("FMS ID is missing");
    }
    $chart_type         =$data['charttype'];
    $chart_title        =$data['charttitle'];
    $chart_subtitle     =$data['chartsubtitle'];
    $chart_x_axis_label =$data['x_axis_label'];
    $chart_y_axis_label =$data['y_axis_label'];
    $chart_x_axis_param =$data['x_axis_param'];
    $chart_y_axis_param =$data['y_axis_param'];
    $chart_from_date    =$data['from_date'];
    $chart_to_date      =$data['to_date'];
    $chart_group_operation=$data['group_operations'];
    $chart_alignement   =$data['chart_alignment'];
    $chart_method_name=loadChartOperations_Gui($link,$chart_group_operation);

    $sql = "
INSERT INTO dashboard_master (
    chart_type,title,subtitle,x_axis,y_axis,x_axis_param1,x_axis_param2,
    from_date,to_date,operations,operation_method,alignment,table_name,fms_id,remarks,chart_status,updated_by,updated_ip,created_at,updated_at
) VALUES (
          '$chart_type','$chart_title','$chart_subtitle','$chart_x_axis_label','$chart_y_axis_label','$chart_x_axis_param','$chart_y_axis_param',
          '$chart_from_date','$chart_to_date','$chart_group_operation','$chart_method_name','$chart_alignement','$tablename',
          '$fms_id','', 1,'$updated_by','$updated_ip',NOW(),NOW())";
    $result = mysqli_query($link,$sql);

    if(!$result){
        throw new GlobalException(mysqli_error($link));
    }

    return mysqli_insert_id($link);
}
function updateChart($link, $data, $chartid, $updated_by, $updated_ip){
    if(!$link){
        throw new GlobalException(mysqli_error($link));
    }

    if(!$chartid){
        throw new GlobalException("Chart ID is missing");
    }

    $chart_type         = $data['charttype'] ?? '';
    $chart_title        = $data['charttitle'] ?? '';
    $chart_subtitle     = $data['chartsubtitle'] ?? '';
    $chart_x_axis_label = $data['x_axis_label'] ?? '';
    $chart_y_axis_label = $data['y_axis_label'] ?? '';
    $chart_x_axis_param = $data['x_axis_param'] ?? '';
    $chart_y_axis_param = $data['y_axis_param'] ?? '';
    $chart_from_date    = $data['from_date'] ?? '';
    $chart_to_date      = $data['to_date'] ?? '';
    $chart_group_operation = $data['group_operations'] ?? '';
    $chart_alignement   = $data['chart_alignment'] ?? '';

    $chart_method_name = null;
    if(!empty($chart_group_operation)){
        $chart_method_name = loadChartOperations_Gui($link, $chart_group_operation);
    }

    $chartid = (int)$chartid;
    $sql = "
    UPDATE dashboard_master SET
        chart_type = '$chart_type',
        title = '$chart_title',
        subtitle = '$chart_subtitle',
        x_axis = '$chart_x_axis_label',
        y_axis = '$chart_y_axis_label',
        x_axis_param1 = '$chart_x_axis_param',
        x_axis_param2 = '$chart_y_axis_param',
        from_date = '$chart_from_date',
        to_date = '$chart_to_date',
        operations = '$chart_group_operation',
        operation_method = '$chart_method_name',
        alignment = '$chart_alignement',
        updated_by = '$updated_by',
        updated_ip = '$updated_ip',
        updated_at = NOW()
    WHERE id = $chartid
    ";
    return mysqli_query($link, $sql) or false;
}


$chart_type='';
$chart_title='';
$chart_subtitle='';
$chart_x_axis_label='';
$chart_y_axis_label='';
$chart_x_axis_param='';
$chart_y_axis_param='';
$chart_from_date='';
$chart_to_date='';
$chart_group_operation='';
$chart_alignement='';
$chart_method_name='';
$chart_data="[
            {
            name: 'M1',
            data: [
                null, null, null, null, null, 2, 9, 13, 50, 170, 299, 438, 841,
                1169, 1703, 2422, 3692, 5543, 7345, 12298, 18638, 22229, 25540,
                28133, 29463, 31139, 31175, 31255, 29561, 27552, 26008, 25830,
                26516, 27835, 28537, 27519, 25914, 25542, 24418, 24138, 24104,
                23208, 22886, 23305, 23459, 23368, 23317, 23575, 23205, 22217,
                21392, 19008, 13708, 11511, 10979, 10904, 11011, 10903, 10732,
                10685, 10577, 10526, 10457, 10027, 8570, 8360, 7853, 5709, 5273,
                5113, 5066, 4897, 4881, 4804, 4717, 4571, 4018, 3822, 3785, 3805,
                3750, 3708, 3708, 3708, 3708
            ]
        }, {
            name: 'M2',
            data: [
                null, null, null, null, null, null, null, null, null,
                1, 5, 25, 50, 120, 150, 200, 426, 660, 863, 1048, 1627, 2492,
                3346, 4259, 5242, 6144, 7091, 8400, 9490, 10671, 11736, 13279,
                14600, 15878, 17286, 19235, 22165, 24281, 26169, 28258, 30665,
                32146, 33486, 35130, 36825, 38582, 40159, 38107, 36538, 35078,
                32980, 29154, 26734, 24403, 21339, 18179, 15942, 15442, 14368,
                13188, 12188, 11152, 10114, 9076, 8038, 7000, 6643, 6286, 5929,
                5527, 5215, 4858, 4750, 4650, 4600, 4500, 4490, 4300, 4350, 4330,
                4310, 4495, 4477, 4489, 4380
            ]
        }]";

// all operation perfrom here
$meta_data['pid']=$_REQUEST['pid']??'';
$meta_data['hid']=$_REQUEST['hid']??'';
$meta_data['id']=$_REQUEST['id']??'';
$meta_data['chartid']=$_REQUEST['chartid']??'';

// when page is open in updating mode
if(isset($_REQUEST['chartid']) && !empty($_REQUEST['chartid'])){
    $chartid=$_REQUEST['chartid'];
    $sql="select * from dashboard_master where id='$chartid'";
    $result=mysqli_query($link1,$sql);
    if(!$result){
        $meta_data['type']="error";
        $meta_data['msg']=mysqli_error($link1);
        $param=http_build_query($meta_data);
        throw new GlobalException($param);
    }
    if(mysqli_num_rows($result)==0){
        $meta_data['type']="error";
        $meta_data['msg']="No Chart Found";
        $param=http_build_query($meta_data);
        throw new GlobalException($param);
    }
    $row=mysqli_fetch_assoc($result);
    $chart_type         =$row['chart_type'];
    $chart_title        =$row['title'];
    $chart_subtitle     =$row['subtitle'];
    $chart_x_axis_label =$row['x_axis'];
    $chart_y_axis_label =$row['y_axis'];
    $chart_x_axis_param =$row['x_axis_param1'];
    $chart_y_axis_param =$row['x_axis_param2'];
    $chart_from_date    =$row['from_date'];
    $chart_to_date      =$row['to_date'];
    $chart_group_operation=$row['operations'];
    $chart_alignement   =$row['alignment'];
    $page_opteration="update_button";
}
$msg=null;
// perform save chart and preview chart option here
if(isset($_POST) && !empty($_POST)){
    $chart_type         =$_POST['charttype'];
    $chart_title        =$_POST['charttitle'];
    $chart_subtitle     =$_POST['chartsubtitle'];
    $chart_x_axis_label =$_POST['x_axis_label'];
    $chart_y_axis_label =$_POST['y_axis_label'];
    $chart_x_axis_param =$_POST['x_axis_param'];
    $chart_y_axis_param =$_POST['y_axis_param'];
    $chart_from_date    =$_POST['from_date'];
    $chart_to_date      =$_POST['to_date'];
    $chart_group_operation=$_POST['group_operations'];
    $chart_alignement   =$_POST['chart_alignment'];

    if(isset($_POST['submit_button'])){
        $gen_chartId=saveChart($link1,$_POST,$_POST['table_name'],$_POST['fms_id'],$_SESSION['userid'],$_SERVER['REMOTE_ADDR']);
        $meta_data['chartid']=$gen_chartId;
        $meta_data['msg']='Saved Succesfully';
        $param=http_build_query($meta_data);
        header("location:gui_master_3.php?$param");exit();
    }
    if(isset($_POST['preview_button'])){
        var_dump("Preview feature is currently under development."); exit();
    }
    if(isset($_POST['update_button'])){
        $isUpdate=updateChart($link1,$_POST,$_POST['chartid'],$_SESSION['userid'],$_SERVER['REMOTE_ADDR']);
        if($isUpdate){
            $msg="Chart Updated Successfully";
        }
        else{
            $msg="Chart not Updated";
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Responsive Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/highcharts.js"></script>
    <script src="../js/highcharts-more.js"></script>
    <script src="../js/funnel.js"></script>
    <script src="../js/exporting.js"></script>
    <script src="../js/highstock.js"></script>
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
            <li class="p-2 bg-gray-200 rounded">This feature will be available in upcoming updates.</li>
        </ul>
    </div>
</div>

<!-- Main Layout -->
<div class="flex h-full">

    <!-- Main Content -->
    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto p-4 md:p-6">
        <h2 class="text-2xl font-bold mb-6"> <?=$data['fmsname']?> Chart Panel</h2>
        <?php if(isset($msg) || $_REQUEST['msg']){
            ?>
            <div class="mb-4 flex items-center justify-between rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                <span><?=$msg??$_REQUEST['msg']?></span>

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
            <input type="hidden" name="fms_id" value="<?=$data['id']??''?>">
            <input type="hidden" name="table_name" value="<?=$data['table_name']??''?>">
            <?php
            if($chartid){
                echo '<input type="hidden" name="chartid" value="'.$chartid.'">';
            }
            ?>
            <div class="space-y-5">
<!--                chart type-->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Chart Type</label>
                    <select  style="text-transform: capitalize" id="charttype" name="charttype"  required onchange="this.form.submit()"
                             class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>-- Select Chart Type --</option>
                        <?php
                        $selected_chart=$chart_type??'';
                        $sql = "SELECT * FROM charts_master WHERE status='1'";
                        $result = mysqli_query($link1, $sql);
                        while($row = mysqli_fetch_assoc($result)) {
                            $selected = ($selected_chart === $row['chart_type']) ? 'selected' : '';
                            echo "<option value='".$row['chart_type']."' $selected>".$row['chart_name']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Chart Title -->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Chart Title</label>
                    <input type="text" id="charttitle" name="charttitle" value="<?=$chart_title??''?>" placeholder="Enter chart title"
                           class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- chart subtitile-->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Chart Subtitle</label>
                    <input type="text" id="chartsubtitle" name="chartsubtitle" value="<?=$chart_subtitle??''?>" placeholder="Subtitle of Chart"
                           class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!--x-axis label name-->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">X-Axis Label</label>
                    <input type="text" id="x_axis_label" name="x_axis_label" value="<?=$chart_x_axis_label??''?>" placeholder="X-axis Name"
                           class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!--Y-axis name-->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Y-Axis Label</label>
                    <input type="text" id="y_axis_label" name="y_axis_label" value="<?=$chart_y_axis_label??''?>"  placeholder="Y-axis Name"
                           class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Parameters -->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">X-Axis Parameter</label>
                    <select  style="text-transform: capitalize" id="x_axis_param" name="x_axis_param" onchange="this.form.submit()"
                             class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                                    $isSelected = ($chart_x_axis_param===$col) ? 'selected' : '';
                                    echo "<option value='$col' $isSelected>$col</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>

<!--                paramters 2-->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Y-Axis Parameter</label>
                    <select  style="text-transform: capitalize" id="y_axis_param" name="y_axis_param" onchange="this.form.submit()"
                             class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                                    $isSelected = ($chart_y_axis_param===$col) ? 'selected' : '';
                                    echo "<option value='$col' $isSelected>$col</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>


<!--                data selection-->
                <div class="space-y-4">

                    <!-- Date Range Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- From Date -->
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">From Date</label>
                            <input type="date" id="from_date" name="from_date" value="<?=$chart_from_date?>"
                                   class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- To Date -->
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">To Date</label>
                            <input type="date" id="to_date" name="to_date" value="<?=$chart_to_date?>"
                                   class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                    </div>

                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-300">Group Operations</label>
                    <select style="text-transform: capitalize"
                            id="group_operations"
                            onchange="this.form.submit()"
                            name="group_operations" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <?php
                        $chart_type = $chart_type ?? '';
                        $select_group_oper=$chart_group_operation??'';

                        if ($chart_type != '') {

                            // Step 1: Get operations JSON
                            $sql = "SELECT operations FROM charts_master WHERE chart_type='".mysqli_real_escape_string($link1, $chart_type)."'";
                            $result = mysqli_query($link1, $sql);

                            if ($result && mysqli_num_rows($result) > 0) {

                                $row = mysqli_fetch_assoc($result);
                                $opsArray = json_decode($row['operations'], true);

                                if (!empty($opsArray)) {

                                    $ids = implode(',', array_map('intval', $opsArray));
                                    $sql2 = "SELECT * FROM chart_operation WHERE id IN ($ids)";
                                    $result2 = mysqli_query($link1, $sql2);

                                    if ($result2 && mysqli_num_rows($result2) > 0) {
                                        while ($op = mysqli_fetch_assoc($result2)) {
                                            $id=$op['id'];
                                            $select=($select_group_oper==$op['id'])?'selected':'';
                                            echo "<option value='".$op['id']."' $select>".$op['operation']."</option>";
                                        }

                                    } else {
                                        echo "<option value=''>No Operations Found</option>";
                                    }

                                } else {
                                    echo "<option value=''>Invalid Operations Data</option>";
                                }

                            } else {
                                echo "<option value=''>No Chart Found</option>";
                            }

                        } else {
                            echo "<option value=''>Select Chart Type First</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Alignmeng -->
                <div class="border-t border-gray-600 pt-4 space-y-4">
                    <div>
                        <label class="block text-sm mb-1 text-gray-300">Alignment</label>
                        <select  id="chart_alignment" name="chart_alignment"
                                 onchange="this.form.submit()"
                                 class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php
                            $select_alignment=$chart_alignement??''
                            ?>
                            <option value="">-- Select Alignment --</option>
                            <option value="left" <?=$select_alignment==='left'?'selected':''?>>Left</option>
                            <option value="center" <?=$select_alignment==='center'?'selected':''?>>Center</option>
                            <option value="right" <?=$select_alignment==='right'?'selected':''?>>Right</option>
                        </select>
                    </div>

                </div>

                <div style="display: flex;flex-direction: row;justify-content: space-around;">
                    <button type="submit" id="preview_button" name="preview_button" class="mx-2 w-full mt-4 bg-blue-600 hover:bg-blue-700 transition rounded-lg py-2 font-medium">
                        Preview
                    </button>
                    <button type="submit" id="save" name="<?=$page_opteration?>" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 transition rounded-lg py-2 font-medium mx-2">
                        <?=$page_opteration==='submit_button'?'Save':'Update'?>
                    </button>
                </div>

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
    <?php
    $type = !empty($chart_type) ? $chart_type : 'area';
    ?>

    let title = '<?= $chart_title ?? 'this is manu pathak' ?>';
    let subtitle = '<?= $chart_subtitle ?? 'this is manu pathak' ?>';
    let xAxisLabel = '<?= $chart_x_axis_label ?? 'this is manu pathak' ?>';
    let yAxisLabel = '<?= $chart_y_axis_label ?? 'this is manu pathak' ?>';

    // Create chart instance
    let chart = Highcharts.chart('container', {
        chart: {
            type: '<?= htmlspecialchars($type) ?>'
        },
        title: {
            text: title
        },
        subtitle: {
            text: subtitle
        },
        xAxis: {
            allowDecimals: false,
            title: {
                text: xAxisLabel
            }
        },
        yAxis: {
            title: {
                text: yAxisLabel
            }
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b>'
        },
        plotOptions: {
            area: {
                pointStart: 1940,
                marker: {
                    enabled: false
                }
            }
        },
        series: <?= $chart_data ?>
    });

    // Live update function
    function updateChart() {
        chart.update({
            title: { text: title },
            subtitle: { text: subtitle },
            xAxis: { title: { text: xAxisLabel } },
            yAxis: { title: { text: yAxisLabel } }
        });
    }

    // Event listeners
    document.getElementById("charttitle").addEventListener("input", function (e) {
        title = e.target.value;
        updateChart();
    });

    document.getElementById("chartsubtitle").addEventListener("input", function (e) {
        subtitle = e.target.value;
        updateChart();
    });

    document.getElementById("x_axis_label").addEventListener("input", function (e) {
        xAxisLabel = e.target.value;
        updateChart();
    });

    document.getElementById("y_axis_label").addEventListener("input", function (e) {
        yAxisLabel = e.target.value;
        updateChart();
    });
</script>

</body>
</html>
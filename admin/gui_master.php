<?php
/**
 *  This page is help to create dashboard for the FMS
 *   - User can create multiple FMS's Dashboard from here
 *   - easy to use to creation this page
 *   Workflow of the Page
 *        Step 1= getting the fms_id and set in the important inputs
 *        Step 2 = if Fms-Id is not found or wrong then show only form and do nothings
 *        Step 3= when user submit all details then save the data and redirect to same back but
 *                hidde now go button
 *       Step 4= dynamic functon or fms_id ke base par chart render karna
 *       step 5= hide go button
 */
require_once("../includes/config.php");
$msg=null;
$type="success";
global $link1;
$back=false;
$flag=false;
set_exception_handler(function($e){
    if($e instanceof GlobalException){
        $errormsg=$e->getMessage();
        header("Location: gui_master.php?{$errormsg}");
        exit;
    }
});

if(isset($_REQUEST['id']) && $_REQUEST['id']!==""){
    $flag=true;
    $data=getFMsbyid($link1,$_REQUEST['id']);
}
$response=[];
$response['pid']=$_REQUEST['pid'];
$response['hid']=$_REQUEST['hid'];
$response['id']=$_REQUEST['id'];
if(isset($_POST['save'])){
    var_dump("Stopped Form Submit");
//    try{
//        if((new SaveChartsData($link1))->saveChartData($_POST)){
//            $msg="Data Saved Successfully";
//            operationtracker($link1,$_SESSION['userid'],'add chart in gui master','ADD','ADD',$_SERVER['REMOTE_ADDR']);
//        }else{
//            $msg="Data not Saved";
//            $type="error";
//        }
//    }catch (Exception $e){
//        $response['msg']=$e->getMessage();
//        $response['error']="error";
//        $param=http_build_query($response);
//        throw new GlobalException($param);
//    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../images/titleimg.png" type="image/png">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/abc.css" rel="stylesheet">
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/moment.js"></script>
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title><?=siteTitle?></title>
    <script src="../js/highcharts.js"></script>
    <script src="../js/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/funnel.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="../js/frmvalidate.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script>
        $("#frm1").validate();
    </script>
    <style>
        .toast {
            position: fixed;
            top: 20px;
            right: -350px;
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(8px);
            color: #fff;
            padding: 14px 18px;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            font-size: 14px;
            font-weight: bold;
            min-width: 250px;
            max-width: 300px;

            transition: all 0.4s ease;
            opacity: 0;
        }

        .toast.show {
            right: 20px;
            opacity: 1;
        }

        .toast .icon {
            font-size: 18px;
        }

        .toast .message {
            flex: 1;
        }
        .toast::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background: #fff;
            animation: progress 60s linear;
        }

        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
</head>
<body>
<?php
if (!empty($msg) || isset($_REQUEST['msg'])) {
    $message = $_REQUEST['msg'] ?? $msg ?? '';
    $type    = $_REQUEST['error'] ?? $type ?? 'success';

    echo showToastUI($message, $type);
}
?>
<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <h2 align="center"><i class="fa fa-users"></i> <?=$data['fmsname']?> Analysis</h2><br/><br/>
            <form  name="frm1" id="frm1" class="form-horizontal" action="" method="post">
                <input type="hidden" name="tablename" value="<?=$data['table_name']?>">
                <input type="hidden" name="fms_id" value="<?=$data['id']?>">
                <input type="hidden" name="id" value="<?=$_REQUEST['id']??''?>">
                <input type="hidden" name="pid" value="<?=$_REQUEST['pid']??''?>">
                <input type="hidden" name="hid" value="<?=$_REQUEST['hid']??''?>">
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="username" class="col-md-6 control-label">
                            Chart Type<span class="red_small">*</span>
                        </label>
                        <div class="col-md-6">

                            <select id="select_chart" name="chart" class="form-control" onchange="this.form.submit()" required>
                                <option value="">-- Select Chart --</option>
                                <?php
                                $selected_chart = $_REQUEST['chart'] ?? '';

                                $sql = "SELECT * FROM charts_master WHERE status='1'";

                                $result = mysqli_query($link1, $sql);
                                while($row = mysqli_fetch_assoc($result)) {
                                    $selected = ($selected_chart == $row['id']) ? 'selected' : '';
                                    echo "<option value='".$row['id']."' $selected>".$row['chart_name']."</option>";
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="col-md-6 control-label">
                            Chart Title<span class="red_small">*</span>
                        </label>
                        <div class="col-md-6">
                            <input class="form-control" id="charttitle" name="charttitle" type="text" placeholder="Chart Title" value="<?=$_REQUEST['charttitle']??''?>" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="username" class="col-md-6 control-label">
                            Parameter 1<span class="red_small">*</span>
                        </label>
                        <div class="col-md-6">
                            <select class="form-control" name="parameter_value" onchange="this.form.submit()" required>
                                <option value="">-- Select Parameter --</option>
                                <?php
                                $tablename = mysqli_real_escape_string($link1, $data['table_name'] ?? '');
                                $selected = $_REQUEST['parameter_value'] ?? '';

                                $exclude = ['id', 'created_date', 'update_date', 'updated_by', 'updated_ip'];

                                $sql = "SHOW COLUMNS FROM `$tablename`";
                                $result = mysqli_query($link1, $sql);

                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $col = $row['Field'];

                                        if (!in_array($col, $exclude)) {
                                            $isSelected = ($selected == $col) ? 'selected' : '';
                                            echo "<option value='$col' $isSelected>$col</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="col-md-6 control-label">
                            Operations<span class="red_small">*</span>
                        </label>
                        <div class="col-md-6">
                            <select class="form-control" style="text-transform: capitalize" name="operations" required  onchange="this.form.submit()">
                                <option value="">-- Select Operation --</option>
                                <?php
                                $selectedOp = $_REQUEST['operations'] ?? '';

                                $sql = "SELECT * FROM `chart_operation` WHERE status='1'";
                                $result = mysqli_query($link1, $sql);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $value = $row['id'];
                                        $label = $row['operation'];

                                        $selected = ($selectedOp == $label) ? 'selected' : '';

                                        echo "<option value='{$label}' {$selected}>{$label}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="username" class="col-md-6 control-label">
                            Parameter 2<span class="red_small">*</span>
                        </label>
                        <div class="col-md-6">
                            <select class="form-control" name="parameter_value_2" onchange="this.form.submit()" required>
                                <option>--Select Parameter --</option>
                                <?php
                                $tablename = mysqli_real_escape_string($link1, $data['table_name'] ?? '');
                                $selected = $_REQUEST['parameter_value_2'] ?? '';

                                $exclude = ['id', 'created_date', 'update_date', 'updated_by', 'updated_ip'];

                                $sql = "SHOW COLUMNS FROM `$tablename`";
                                $result = mysqli_query($link1, $sql);

                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $col = $row['Field'];

                                        if (!in_array($col, $exclude)) {
                                            $isSelected = ($selected == $col) ? 'selected' : '';
                                            echo "<option value='$col' $isSelected>$col</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="col-md-6 control-label">
                            Remark<span class="red_small">*</span>
                        </label>
                        <div class="col-md-6">
                            <textarea class="form-control" rows="3" name="remark" required><?=$_REQUEST['remark']??''?></textarea>
                        </div>
                    </div>
                </div>
                <div class="text-center" style="margin-top: 20px;">
                    <?php
                    if(isset($data['id']) && !$back){
                        echo '<button type="submit" name="save" class="btn btn-success" style="margin: 20px;">Go</button>';
                    }else{
                        $path="";
                        echo '<button type="button" class="btn btn-success" style="margin: 20px;" onclick="window.location.href=">back</button>';
                    }
                    ?>
                </div>
            </form>
            <?php

            if (
                    isset($_POST['save']) &&
                    !empty($flag) &&
                    !empty($_REQUEST['chart']) &&
                    !empty($_REQUEST['parameter_value']) &&
                    !empty($_REQUEST['parameter_value_2']) &&
                    !empty($_REQUEST['operations'])
            ) {

            ?>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="fms_other_chart"></div>
                            <!-- ["PIE","BAR","LINE","AREA","SCATTER","COLUMN"] -->
                            <?=renderChart($_REQUEST['chart']??'COLUMN',
                                $_REQUEST['charttitle']??'Form Input Data',
                                $_REQUEST['charttitle']??'',
                                    'left',
                                    'fms_other_chart',$_REQUEST['operations']??'',
                            $_REQUEST['parameter_value']??'',$_REQUEST['parameter_value_2']??'',$data['id'])?>
                        </div>
                    </div>
                </div>
            <?php }?>

        </div>

    </div>
</div>
<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
</body>
</html>
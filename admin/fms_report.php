<?php
require_once("../includes/config.php");
global $link1;
if(isset($_REQUEST['id'])){
    $id=base64_decode($_REQUEST['id']);
//    var_dump($id);
//    $data_fms=FMsBasicOperation::loadFMS($link1,$id);
//    var_dump($data_fms['table_name']);
}
$fms_reports=new FMSReports($link1);
$msg=null;
$flag=false;
$report_container=[];

if(isset($_POST['go'])){
    $id=$_POST['fms_id'];
    $date_range=$_POST['daterange'];
    $date_range=explode(" - ",$date_range);
    $data_fms=FMsBasicOperation::loadFMS($link1,$id);
    $tabale_name=$data_fms['table_name'];
    $report_data=$fms_reports->showReports($data_fms['table_name'],$date_range);
    if($report_data){
        if($report_data!==2){
            $report_container[]=$report_data;
            $flag=true;
        }
        else{
            $msg="No Data Found";
        }
    }
    else{
        throw new GlobalException("Some things is Wrong");
    }
}
$keys=[];
if($flag){
    $keys=array_keys($report_container[0][0]);
}


//all ui function here
function printTableKeys($keys){
    $remove = ['id', 'created_date', 'update_date', 'updated_by', 'updated_ip'];
    $filteredKeys = array_values(array_filter($keys, function($col) use ($remove) {
        return !in_array($col, $remove);
    }));
    $row = '<tr>';
    $row .= '<th scope="col">#</th>';
    foreach($filteredKeys as $key){
        $label = ucwords(str_replace("_", " ", $key));
        $row .= '<th scope="col">' . $label . '</th>';
    }
    $row .= "</tr>";
    return $row;
}


$isPermission=PermissionManager::checkdownloadRights($link1,$_SESSION['userid'],$_REQUEST['pid']);

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

    <script src="../js/frmvalidate.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/common_js.js"></script>
    <script type="text/javascript" src="../js/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/daterangepicker.css"/>
    <link rel="stylesheet" href="../css/datepicker.css">
    <!-- Include multiselect -->
    <title><?=siteTitle?></title>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" >
        $(document).ready(function(){
            $('input[name="daterange"]').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
        });
    </script>
</head>
<body>
<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <h2 align="center"><i class="fa fa-pencil-square-o"></i> FMS Report </h2>

            <?php
            if($msg){
                echo $msg;
            }
            ?>
            <?php
            if($isPermission){
            ?>
            <div class="container-fluid" style="margin-top: 50px">
                <div class="row">
                    <div class="col">
                        <form class="form-horizontal" role="form" name="form1"  id="form1" action="" method="post">
                            <input name="fms_id" value="<?=$id?>" type="hidden">
                            <div class="form-group">
                                <div id= "dt_range" class="col-md-6"><label class="col-md-5 control-label">Date Range</label>
                                    <div class="col-md-6 input-append date" align="left">
                                        <input type="text" name="daterange" id="date_rng" class="form-control" value="<?=$_REQUEST['daterange']?>" />
                                    </div>
                                </div>
                                <div class="col-md-6"><label class="col-md-5 control-label"></label>
                                    <div class="col-md-5" align="left">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="go" class="btn btn-success">Go</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                if($flag){
                ?>
                    <div class="row">
                        <div class="col" style="text-align: end">
                            <a href="../excelReports/fms_reports.php?tab=<?=base64_encode($tabale_name)?>&time=<?=$_POST['daterange']?>" class="btn btn-success">Download Excel File</a>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 30px">
                        <div class="col">
                            <table id="report_generation" class="table table-hover" width="100%" style="margin-top: 10px"  align="center" cellpadding="4" cellspacing="0" border="1">
                                <thead class="bg-primary">
                                <?= printTableKeys($keys) ?>
                                </thead>
                            </table>
                        </div>
                    </div>
                <?php }
                ?>
            </div>
            <?php }else { ?>
                <div  style="display: flex;justify-content: center;padding: 20px;">
                    <div class="d-flex justify-content-center align-items-center" style="height:70vh;">
                        <div class="card shadow-lg text-center" style="max-width: 420px; border-radius:15px;">
                            <div class="card-body">
                                <div style="font-size:60px; color:#dc3545;">
                                    <i class="fa fa-lock"></i>
                                </div>
                                <h3 class="mt-3" style="font-weight:600;">Access Denied</h3>
                                <p class="text-muted mt-2">
                                    You don’t have permission to access this page.<br>
                                    Please contact your administrator if you believe this is a mistake.
                                </p>

                                <div class="mt-4">
                                    <a href="fms_view.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>" class="btn btn-primary">
                                        <i class="fa fa-arrow-left"></i> Go Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</div>
<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
<?php
if($flag){
?>
    <script>
        $(document).ready(function () {
            $('#report_generation').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "../pagination/fms_report-data-grid.php",
                    type: "POST",
                    data: {
                        pid: "<?= $_REQUEST['pid'] ?? '' ?>",
                        hid: "<?= $_REQUEST['hid'] ?? '' ?>",
                        tableaname:"<?=$tabale_name?>",
                        data_range:"<?=$_POST['daterange']?>"
                    },
                    error: function () {
                        $("#report_generation").append(
                            '<tbody><tr><td colspan="10">No data found</td></tr></tbody>'
                        );
                    }
                }
            });
        });
    </script>
<?php } ?>
</body>
</html>
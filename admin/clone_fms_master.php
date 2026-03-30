<?php
require_once("../includes/config.php");
global $link1,$db;
set_exception_handler(function ($exception) {
    if ($exception instanceof FMSExceptionHandler) {
        $exception->location();
        exit();
    }
    if ($exception instanceof GlobalException) {
        $msg = urlencode($exception->getMessage());
        header("Location: add_fms_master.php?op=add&pid=1&hid=1&msg={$msg}");
        exit();
    }
});

$pid = isset($_REQUEST['pid']) ? (int)$_REQUEST['pid'] : 0;
$hid=isset($_REQUEST['hid'])?$_REQUEST['hid']:'';
$fmsid=isset($_REQUEST['id'])?$_REQUEST['id']:'';
$location="fms_master.php";

$fms=new FMS_Operations($pid,$hid,$location,$link1);

if(isset($_POST['clone_system'])){
    $fmsName=$_POST['fmsname'];
    $fms_details=$_POST['fms_details'];
    $fms_steps=$_POST['steps'];
    $fms_totalform=$_POST['total_form'];
    $fmsIP=$_SERVER['REMOTE_ADDR'];


    $value=$fms->tablenameAlreadyExists($db,$fms->spaceRemover($fmsName));
    $value=(int)$value;
    if($value===1){
//        throw new GlobalException("Table Already Exist");
        var_dump("Table Already Exists");
        exit();
    }
    /*
     *  1. Table create
     *  2. tableName
     *  3. Fms_master me inserting
     *  4. Form_master -> fms-creates
     */

    $fmsclone=new FMSClone($link1,$fmsid);
    $fmsclone->setTableName($fms->spaceRemover($fmsName));
    $newFmsid=$fmsclone->fmsClone($fmsName,$fms_details,$fms_steps,$fms_totalform,$fmsIP);

    $ook=$fmsclone->formCloningStart($newFmsid);
    if($ook){
        header("Location: fms_master.php?pid=$pid&hid=$hid&type=success&msg={Successfully Cloned}");
        exit();
    }


}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=siteTitle?></title>
    <meta http-equiv="refresh" content="1800">
    <link rel="shortcut icon" href="../images/titleimg.png" type="image/png">
    <script src="../js/jquery.js"></script>
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/abc.css" rel="stylesheet">
    <script src="../js/bootstrap.min.js"></script>
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script>
        $(document).ready(function(){
            $("#frm1").validate();
        });
    </script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <!-- Include Date Picker -->
    <script type="text/javascript" src="../js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-multiselect.css" type="text/css"/>
</head>
<body>

<div class="container-fluid">
    <div class="row content">
        <?php include("../includes/leftnav2.php"); ?>
        <div class="<?=$screenwidth?>">
            <h2 align="center"><i class="fa fa-users"></i> Clone FMS</h2><br/><br/>


                <div class="form-group"  id="page-wrap" style="margin-left:10px;" >
                    <form  name="frm1" id="frm1" class="form-horizontal" action="" method="post">
                        <input type="hidden" name="fms_id" value="<?= $fmsid ?>">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="user_id" class="col-md-6 control-label">
                                    Name<span class="red_small">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input name="fmsname" type="text" class="form-control"
                                           value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="col-md-6 control-label">Details<span class="red_small">*</span></label>
                                <div class="col-md-6">
                                    <input name="fms_details" type="text" class="form-control" value="" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6"><label class="col-md-6 control-label">No of Steps</label>
                                <div class="col-md-6">
                                    <input name="steps" type="number" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6"><label class="col-md-6 control-label">Total No form<span class="red_small">*</span></label>
                                <div class="col-md-6">
                                    <input name="total_form" type="number" class="form-control" value="" required>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="text-center mt-5">
                    <button type="submit" name="clone_system"  class="btn btn-success">
                        Clone
                    </button>
                    <span class="btn btn-primary" onclick="window.location.href='fms_master.php?pid=290&hid=Masters'">
                    <span id="operation_name">Cancel</span>
                </span>
                </div>
                </form>



        </div>
    </div>
</div>
</div>
<div id="customAlertContainer"></div>
<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
<script>
    function showAlert(message, type = "success", duration = 3000) {
        const container = document.getElementById("customAlertContainer");
        const alert = document.createElement("div");
        alert.classList.add("alert-box", `alert-${type}`);
        alert.innerHTML = `
        <span>${message}</span>
        <span class="close-btn">&times;</span>
    `;
        container.appendChild(alert);
        alert.querySelector(".close-btn").addEventListener("click", () => {
            alert.remove();
        });
        setTimeout(() => {
            alert.remove();
        }, duration);
    }
    document.addEventListener("DOMContentLoaded",function(){
        document.getElementById("frm1").addEventListener("submit",function (e){
            // e.preventDefault();
        });
    });
    function validation(value='',message=''){
        if(value===''){
            showAlert(message,"error");
            return;
        }
    }
    <?php
    if(isset($_REQUEST['msg'])){
        echo 'showAlert("'.htmlspecialchars($_REQUEST['msg']).'","error",7000);';
    }
    ?>
</script>

<script>
    document.querySelectorAll("input").forEach((cell) => {
        cell.style.textTransform = "capitalize";
    });
</script>
</body>
</html>
<?php
/**
 *  Workflow of clone_fms_master page
 *     Step 1 = get all data based on fms_id from fms_master
 *     step 2= set important  data into hidden field in form
 *     step 3 = when user submit details then you will use it
 */

require_once("../includes/config.php");
global $link1,$db;

/**
 * Globally Exception handler
 */
set_exception_handler(function ($exception) {
    if ($exception instanceof GlobalException) {
        $msg = $exception->getMessage();
        header("Location: clone_fms_master.php?type=error&msg=$msg");
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
//    $fms_details=$_POST['fms_details'];
//    $fms_steps=$_POST['steps'];
//    $fms_totalform=$_POST['total_form'];
    $fmsIP=$_SERVER['REMOTE_ADDR'];

    $fmsid=$_POST['fms_id'];


    $data = [
            'pid'    => $pid,
            'hid'    => $hid,
    ];


    $value=$fms->tablenameAlreadyExists($db,$fms->spaceRemover($fmsName));

    $value=(int)$value;
    if($value===1){
        $data['id']=$_POST['fms_id'];
        $data['type']='error';
        $data['msg']='Table Name Already Exists';
        $params = http_build_query($data);
        header("Location: clone_fms_master.php?$params");
        exit;
    }
    /*
     *  1. Table create
     *  2. tableName
     *  3. Fms_master me inserting
     *  4. Form_master -> ArrayList-creates
     */

    $fmsclone=new FMSClone($link1,$fmsid);
    $fmsclone->setTableName($fms->spaceRemover($fmsName));
    $newFmsid=$fmsclone->fmsClone($fmsName,'','','',$fmsIP);

    $ook=$fmsclone->formCloningStart($newFmsid);
    if($ook){
        header("Location: fms_master.php?pid=$pid&hid=$hid&type=success&msg=Successfully Cloned");
        exit();
    }else{
        header("Location: fms_master.php?pid=$pid&hid=$hid&type=error&msg=Somethings is wrong Fms Cloning");
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
    <script>
        window.addEventListener("load", function() {
            const toast = document.getElementById("errorPopup");
            if (toast) {
                setTimeout(() => {
                    toast.classList.add("show");
                }, 300); // small delay for smooth entry

                setTimeout(() => {
                    toast.classList.remove("show");
                }, 50000); // hide after 3s
            }
        });
    </script>
</head>
<body>

<?php
if(isset($_REQUEST['msg'])){?>
    <div id="errorPopup" class="toast" style="background-color: <?= isset($_REQUEST['type']) && $_REQUEST['type']==='error'?'darkred':'green' ?>">
        <span class="icon">⚠️</span>
        <span class="message"><?=htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8');?></span>
    </div>
<?php } ?>

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
<!--                            <div class="col-md-6">-->
<!--                                <label for="username" class="col-md-6 control-label">Details<span class="red_small">*</span></label>-->
<!--                                <div class="col-md-6">-->
<!--                                    <input name="fms_details" type="text" class="form-control" value="" required>-->
<!--                                </div>-->
<!--                            </div>-->
                        </div>

<!--                        <div class="form-group">-->
<!--                            <div class="col-md-6"><label class="col-md-6 control-label">No of Steps</label>-->
<!--                                <div class="col-md-6">-->
<!--                                    <input name="steps" type="number" class="form-control" required>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="col-md-6"><label class="col-md-6 control-label">Total No form<span class="red_small">*</span></label>-->
<!--                                <div class="col-md-6">-->
<!--                                    <input name="total_form" type="number" class="form-control" value="" required>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                </div>
                <div class="text-center mt-5">
                    <?php
                    if(isset($_REQUEST['id'])){
                        echo '<button type="submit" name="clone_system"  class="btn btn-success">
                        Clone
                    </button>';
                    }
                    ?>
                    <span class="btn btn-primary" onclick="window.location.href='fms_master.php?pid=290&hid=Masters'">
                    <span id="operation_name">Cancel</span>
                </span>
                </div>
                </form>
        </div>
    </div>
</div>
</div>

<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>

<script>
    document.querySelectorAll("input").forEach((cell) => {
        cell.style.textTransform = "capitalize";
    });
</script>
</body>
</html>
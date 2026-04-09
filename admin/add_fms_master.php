<?php
require_once("../includes/config.php");
global $link1;
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

$op  = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'add';
$pid = isset($_REQUEST['pid']) ? (int)$_REQUEST['pid'] : 0;
$hid=isset($_REQUEST['hid'])?$_REQUEST['hid']:'';
$location="fms_master.php";
$is_edit = ($op === 'edit');
$fms_id=isset($_REQUEST['id'])?base64_decode($_REQUEST['id']):'';

$fms=new FMS_Operations($pid,$hid,$location,$link1);
$show=null;

function createTableDMS($link1,$name){
    try {
        $tableName=$name;
        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            update_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by varchar(10),
    updated_ip varchar(40)
        ) ENGINE=InnoDB";

        if (!mysqli_query($link1,$sql)) {
            throw new GlobalException("Error creating table: " . $link1->error);
        }

        return true;

    } catch (Exception $e) {
        throw new GlobalException($e->getMessage());
    }
}

/**
 *  Add the FRM Form Data
 *   Step 1= get All the in single unit (Array)
 *   Step 2= add in  FMs_operation-> addOperation method
 *   Step 3=  addOperation -> return array(status,msg)
 *   step 4= accoding to status you will perform next task
 */
if(isset($_POST['add'])){

    $data=[];
    $data['fmsname']=$_POST['fmsname'];
    $data['details']=$_POST['fms_details'];
    $data['steps']=$_POST['steps'];
    $data['total_form']=$_POST['total_form'];
    $data['ip']=$_SERVER['REMOTE_ADDR'];
    $data['category']=$_POST['category'];
    $tablename=$fms->spaceRemover($data['fmsname']);
    $tablename='fms_'.$tablename;



    // here we can check table is already exsits or not
    $str=$fms->checkAlreadyExist($data['fmsname']);

    // check data is already table exist or not
    if(in_array($tablename,$str)){
        throw new GlobalException("Table already exist");
    }
    // if not then create a table
    if(!$table=$fms->createTable($data['fmsname'])){
        throw new GlobalException("Table is not created, Somethings things happened");
    }

    /*
     * this block of code is checking that category is
     *  0(FMS) then category is fms_tableName
     *  1(other) then category is dyo_table_name
     *       case 1= check the already is exist or not
     *       case 2= if not then create table in db
     */
    if($data['category']==='0'){
        $data['category']=$tablename;
    }else if ($data['category']==='1'){
        $data['category']='dy0_'.$tablename.'_master';
        $str_1=$fms->checkAlreadyExist($data['category']);
        // check data is already table exist or not
        if(in_array($data['category'],$str_1)){
            throw new GlobalException("Table already exist");
        }else{
            createTableDMS($link1,$data['category']);
        }
    }else{

    }


    try{

        $response=$fms->addOperation($data,$_SESSION['userid'],$tablename);
        $flag = dailyActivity($_SESSION['userid'],$data['fmsname'],"ArrayList","CREATE",$_SERVER['REMOTE_ADDR'],$link1,true);
        // $res23=($response['status'] && $flag)
        if($response['status'] && $flag){
            $fms->redirect("success",$response['msg']);
        }else{
            $fms->redirect("error",$response['msg']);
        }
    }catch (Exception $e){
        throw new FMSExceptionHandler("error","some things is wrong",$location,$pid,$hid);
    }
}

/**
 *  Update the FRM Form Data
 *   Step 1= get All the in single unit (Array)
 *   Step 2= call the  FMs_operation-> updateOperation method
 *            updateOperation method  @param array data , userid , frm_is
 *   Step 3=  updateOperation -> return array(status,msg)
 *   step 4= accoding to status you will perform next task
 */
if(isset($_POST['update'])){
    $data=[];
    $data['fmsname']=$_POST['fmsname'];
    $data['details']=$_POST['fms_details'];
    $data['steps']=$_POST['steps'];
    $data['total_form']=$_POST['total_form'];
    $data['ip']=$_SERVER['REMOTE_ADDR'];
    $data['category']=$_POST['category'];

    $tablename=$fms->spaceRemover($data['fmsname']);
    $tablename='fms_'.$tablename;

    if($data['category']==='0'){
        $data['category']=$tablename;
    }
    else if ($data['category']==='1'){
        $data['category']='dy0_'.$tablename.'_master';
        $str_1=$fms->checkAlreadyExist($data['category']);
        if(in_array($data['category'],$str_1)){
            // do nothing
            var_dump("do nothinhs");
        }else{
            createTableDMS($link1,$data['category']);
            var_dump("create table");
        }
    }else{

    }

    try{
        $resUp=$fms->updateOperation($data,$_SESSION['userid'],$fms_id);
        $flag = dailyActivity($_SESSION['userid'],$data['fmsname'],"ArrayList","UPDATED",$_SERVER['REMOTE_ADDR'],$link1,true);
        if($resUp['status'] &&  $flag){
            $show=$resUp['msg'];
//            header("location:add_fms_master.php?pid=$pid&hid=$hid&msg=".$response['msg']);
//            exit();
        }else{
            $show=$resUp['msg'];
//            header("location:add_fms_master.php?pid=$pid&hid=$hid&msg=".$response['msg']);
//            exit();
        }
    }catch (Exception $e){
        throw new FMSExceptionHandler("error","some things is wrong",$location,$pid,$hid);
    }
}

/**
 *  This block help to load FRMs Data based of ID
 *  and majorly used in Updating time frms data loading into the page
 */
if($fms_id){
    $load_fms=mysqli_query($link1,"select * from fms_master where id=$fms_id");
    $edit_data=mysqli_fetch_assoc($load_fms);
}


$isPermissionGrant=false;

if($is_edit){
    $isPermissionGrant=PermissionManager::checkEditRights($link1,$_SESSION['userid'],$_REQUEST['pid']);
}else{
    $isPermissionGrant=PermissionManager::checkaddRights($link1,$_SESSION['userid'],$_REQUEST['pid']);
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
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .modal-box {
            background: #fff;
            width: 380px;
            border-radius: 12px;
            padding: 25px 20px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
            animation: slideDown 0.3s ease;
        }
        .modal-body h2 {
            margin-bottom: 10px;
        }
        .modal-footer {
            margin-top: 20px;
        }
        @keyframes slideDown {
            from {
                transform: translateY(-40px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
    <!--    alert box css-->
    <style>
        #customAlertContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .alert-box {
            min-width: 250px;
            max-width: 90vw;
            padding: 12px 16px;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideIn 0.3s ease;
        }

        .alert-success { background: #28a745; }
        .alert-error { background: #dc3545; }
        .alert-warning { background: #ffc107; color: #000; }

        .close-btn {
            margin-left: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* 📱 Mobile tweak */
        @media (max-width: 600px) {
            #customAlertContainer {
                top: auto;
                bottom: 20px;
                right: 10px;
                left: 10px;
            }

            .alert-box {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row content">
        <?php include("../includes/leftnav2.php"); ?>
        <div class="<?=$screenwidth?>">
            <h2 align="center"><i class="fa fa-users"></i> <?=$op==='edit'?'Update':'Add'?> FMS</h2><br/><br/>

            <?php if($show != null): ?>
                <div id="customModal" class="modal">
                    <div class="modal-box">

                        <div class="modal-body">
                            <h2><?php echo htmlspecialchars($show); ?></h2>
                        </div>

                        <div class="modal-footer">
                            <button onclick="redirect()" class="btn" style="background-color: red;color: white" id="cancelBtn">Ok</button>
                        </div>
                        <script>
                            function redirect(){
                                window.location.href="fms_master.php?pid=290&hid=Masters";
                            }
                        </script>
                    </div>
                </div>
            <?php endif; ?>


            <?php
            if($isPermissionGrant){
            ?>

            <div class="form-group"  id="page-wrap" style="margin-left:10px;" >
                <form  name="frm1" id="frm1" class="form-horizontal" action="" method="post">
                    <input type="hidden" name="csrf_token"
                           value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="user_id" class="col-md-6 control-label">
                                Name<span class="red_small">*</span>
                            </label>
                            <div class="col-md-6">
                                <input id="fmsname" name="fmsname" type="text" class="form-control"
                                       value="<?= $is_edit ? $edit_data['fmsname'] : '' ?>"
                                        <?= $is_edit ? '' : '' ?> required>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="username" class="col-md-6 control-label">Category<span class="red_small">*</span></label>
                            <div class="col-md-6">
                                <select id="select_category" name="category" class="form-control">
                                    <?php
                                    $selected_fms = '';
                                    $selected_other = '';

                                    if ($is_edit) {
                                        if (strpos($edit_data['category'], 'fms')===0) {
                                            $selected_fms = 'selected';
                                        }
                                        if (strpos($edit_data['category'], 'dy')===0) {
                                            $selected_other = 'selected';
                                        }else{
                                            $selected_fms = 'selected';
                                        }
                                    } else {
                                        // default when not editing
                                        $selected_fms = 'selected';
                                    }

                                    ?>

                                    <option value="0" <?= $selected_fms ?>>FMS</option>
                                    <option value="1" <?= $selected_other ?>>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="username" class="col-md-6 control-label">Details<span class="red_small">*</span></label>
                            <div class="col-md-6">
                                <input name="fms_details" type="text" class="form-control"
                                       value="<?= $is_edit ? $edit_data['details'] : '' ?>"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6"><label class="col-md-6 control-label">No of Steps</label>
                            <div class="col-md-6">
                                <input name="steps" type="number" class="form-control" min="0" max="99" value="<?= $is_edit ? $edit_data['steps'] : '' ?>" required>

                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="col-md-6"><label class="col-md-6 control-label">Total No form<span class="red_small">*</span></label>
                            <div class="col-md-6">
                                <input name="total_form" type="number" class="form-control" min="0" max="99" value="<?= $is_edit ? $edit_data['total_form'] : '' ?>" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="text-center mt-5">
                <button type="submit" name="<?= $is_edit ? 'update' : 'add' ?>"  class="btn btn-success">
                    <?= $is_edit ? 'Update' : 'Add' ?>
                </button>
                <span class="btn btn-primary" onclick="window.location.href='fms_master.php?pid=290&hid=Masters'">
                    <span id="operation_name">Cancel</span>
                </span>
            </div>
            </form>

            <?php
            }
            ?>

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
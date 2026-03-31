<?php
/*
 * Page Overview:
 * Task 1:
 * This page retrieves form details from the `form_master` table using the FormID.
 *  Based on the fetched configuration, dynamic input fields are generated and displayed.
 *  The received inputs are then processed and added accordingly.
 *
 * Task 2:
 * The generated input fields are assigned appropriate values.
 * Validation is applied to ensure data accuracy and integrity before further processing.
 *
 */

require_once("../includes/config.php");
global $link1;

$formview=new FormView($link1);
$data=[];
if(isset($_REQUEST['formid'])){
    $formid = base64_decode($_REQUEST['formid']);
    $data=$formview->loadform($formid);
    $fms_de=fmsloading($link1,$data['fms_id']);
//    var_dump($fms_de);
}


if(isset($_POST['save'])){

    $data=$formview->loadform($formid);
    $total=count(json_decode($data['parameter_name']));


    $parameter[]=json_decode($data['parameter_name'],true);


    $parameter_1=$parameter[0];
    $data_save=[];

    for($i=0;$i<$total;$i++){
        $data_save[]=$_POST[$parameter_1[$i]];
    }
    $parameter_1[]="updated_by";
    $parameter_1[]="updated_ip";
    $data_save[]=$_SESSION['userid'];
    $data_save[]=$_SERVER['REMOTE_ADDR'];

    $save=$formview->saveDataintable($_POST['table_name'],$parameter_1,$data_save);

    if($save){
        $tablename=$_POST['table_name'];
        header("location:fms_view.php?msg=saved data successfully in $tablename table");
        exit();
    }
    else{
        throw new GlobalException("Data is not saved");
    }
}

$isPermission=PermissionManager::checkaddRights($link1,$_SESSION['userid'],$_REQUEST['pid']);

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

    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-multiselect.css" type="text/css"/>

</head>
<body>

<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?>">
            <h2 align="center"><i class="fa fa-users"></i> Form View </h2><br/><br/>
            <div class="form-group"  id="page-wrap" style="margin-left:10px;" >
                <?php
                if($isPermission){
                ?>
                <form  name="frm1" id="frm1" class="form-horizontal" action="" method="post">
                    <input name="fmsid" value="<?=$fms_de['id']?>" type="hidden"/>
                    <input name="fmsname" value="<?=$fms_de['fmsname']?>" type="hidden"/>
                    <input name="table_name" value="<?=$fms_de['table_name']?>" type="hidden"/>
                    <input name="formid_value_h" value="<?=$data['id']?>" type="hidden"/>
                    <?php
                    $formview->viewFrom($data);
                    ?>

                    <div class="text-center mt-5">
                        <button type="submit" name="save" class="btn btn-primary">Add</button>
                        <span  class="btn btn-primary" onclick="window.location.href='fms_view.php?pid=292&hid=Masters'"><span id="operation_name">Cancel</span</span>
                    </div>
                </form>
                <?php }else{
                ?>
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
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</div>

<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
<script>
    document.querySelectorAll("label").forEach((label)=>{
        label.style.textTransform="capitalize"
    });
    document.querySelectorAll("input").forEach((label)=>{
        label.style.textTransform="capitalize"
    });

</script>
</body>
</html>
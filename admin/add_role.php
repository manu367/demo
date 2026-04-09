<?php
require_once("../includes/config.php");
global $link1;

set_exception_handler(function ($exception) {

    if ($exception instanceof GlobalException) {
        var_dump($exception->getMessage());
        exit();
    }
});

$op=isset($_REQUEST['op'])?$_REQUEST['op']:'add';
$rold_id=isset($_REQUEST['rid'])?$_REQUEST['rid']:'';

// initialization the variable here
$role_master=new RoleMaster($link1);
$pid=$_REQUEST['pid']??'';
$hid=$_REQUEST['hid']??'';

$data = [
    'pid'    => $pid,
    'hid'    => $hid
];

$isPermission=false;
if($op==='edit'){
    $isPermission=PermissionManager::checkViewRights($link1,$_SESSION['userid'],$_REQUEST['pid']);
    if($rold_id===''){
        throw new GlobalException('Role is null');
    }
    $role_data=$role_master->showRoles($rold_id);
}
if(isset($_POST['add'])){

    $isSave=$role_master->saveRole($_POST['role_name'],$_POST['role_type']);
    if($isSave){
        operationtracker($link1,$_SESSION['userid'],'add_role',"Add Role",'ADD',$_SERVER['REMOTE_ADDR']);
        $data['type']='success';
        $data['msg']='Role saved successfully';
        $params = http_build_query($data);
        header("Location: role_master.php?$params");
        exit;
    }else{
        $data['type']='error';
        $data['msg']='Role is not Saved';
        $params = http_build_query($data);
        header("Location: role_master.php?$params");
        exit;
    }
}
if(isset($_POST['edit'])){
    $status=$role_master->editRoleMaster($_POST);

    if($status){
        operationtracker($link1,$_SESSION['userid'],'add_role',"Update Role",'UPDATE',$_SERVER['REMOTE_ADDR']);
        $data['type']='success';
        $data['msg']='Role updated successfully';
        $params = http_build_query($data);
        header("Location: role_master.php?$params");
        exit;
    }else{
        $data['type']='error';
        $data['msg']='User Role not Updated';
        $params = http_build_query($data);
        header("Location: role_master.php?$params");
        exit;
    }
}
$op!=='edit'?$isPermission=true:'';
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
            <h2 align="center"><i class="fa fa-users"></i> <?=$op==='edit'?'Update':'Add'?> Role</h2><br/><br/>

            <?php

            if($isPermission){?>
                <form action="" method="post">
                    <input type="hidden" name="role_id" value="<?=$rold_id?>">
                    <div class="form-group">
                        <div class="col-md-6"><label class="col-md-5 control-label">User Type <span class="red_small">*</span></label>
                            <div class="col-md-5">
                                <input name="role_name" id="role_name" class="form-control" value="<?=$role_data['utype']?$role_data['utype']:''?>">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <label class="col-md-5 control-label">Type <span class="red_small">*</span></label>
                            <div class="col-md-5">
                                <input class="form-control" name="role_type" id="role_type" value="<?=$role_data['type']?$role_data['type']:''?>">
                            </div>

                        </div>
                    </div>
                    <div style="text-align: center;padding: 20px">
                        <button type="submit" name="<?=$op?>" class="btn btn-primary" style="margin-top: 30px"><?=$op==='edit'?'Update':'Add'?></button>
                        <a href="role_master.php?pid=<?=$pid?>&hid=<?=$hid?>" class="btn btn-warning" style="margin-top: 30px">Cacel</a>
                    </div>
                </form>
                <?php }else{
                PermissionManager::accessDenied($_REQUEST['pid'],$_REQUEST['hid'],'role_master.php');
            } ?>
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
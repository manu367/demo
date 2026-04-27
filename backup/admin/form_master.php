<?php
require_once("../includes/config.php");

set_exception_handler(function($e){
    if($e instanceof GlobalException){
        $msg = urlencode($e->getMessage());
        header("Location: fms_master.php?msg={$msg}");
        exit;
    }
});

$fsm_id=base64_decode($_REQUEST['id']);
function loadFSM($link,$sql){
    $result=null;
    try{
        $result=mysqli_query($link,$sql);
    }catch (Exception $e){
        throw new GlobalException("Error loading data: ".$e->getMessage());
    }
    if($result){
        $row=mysqli_fetch_assoc($result);
        return $row;
    }
    return false;
}

$load=loadFSM($link1,"SELECT * FROM fms_master where id=$fsm_id");

if($load){
//    var_dump($load);
}else{
   throw new GlobalException("Id is Not valid");
}


//$isPermissionGrant=false;
//
//if(true){
//    $isPermissionGrant=PermissionManager::checkEditRights($link1,$_SESSION['userid'],$_REQUEST['pid']);
//}else{
//    $isPermissionGrant=PermissionManager::checkaddRights($link1,$_SESSION['userid'],$_REQUEST['pid']);
//}



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
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#frm1").validate();
        });
    </script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <!-- Include Date Picker -->
    <script type="text/javascript" src="../js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-multiselect.css" type="text/css"/>

    <link rel="stylesheet" href="../css/dataTables.responsive.css">
    <script type="text/javascript" src="../js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#myacc-users-grid').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "../pagination/form-data-grid.php",
                    type: "POST",
                    data: {
                        pid: "<?= $_REQUEST['pid'] ?? '' ?>",
                        hid: "<?= $_REQUEST['hid'] ?? '' ?>",
                        id:"<?=$load['id']?>"
                    },
                    error: function () {
                        $("#myacc-users-grid").append(
                            '<tbody><tr><td colspan="10">No data found</td></tr></tbody>'
                        );
                        // $("#myacc-users-grid_processing").hide();
                    }
                }
            });
        });
    </script>
</head>
<body>

<div class="container-fluid">
    <div class="row content">
        <?php include("../includes/leftnav2.php"); ?>
        <div class="<?=$screenwidth?>">
            <h2 align="center"><i class="fa fa-users"></i> Form Details</h2><br/><br/>

            <div class="form-group"  id="page-wrap" style="margin-left:10px;" >
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="user_id" class="col-md-6 control-label">
                                FMS Name<span class="red_small">*</span>
                            </label>
                            <div class="col-md-6">
                                <input name="fmsname" type="text" class="form-control" disabled
                                       value="<?= $load['fmsname']!==null?$load['fmsname']:''?>"
                                    <?= $is_edit ? '' : '' ?> required>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="username" class="col-md-6 control-label">Details<span class="red_small">*</span></label>
                            <div class="col-md-6">
                                <input name="fms_details" type="text" class="form-control" disabled
                                       value="<?= $load['details']!==null?$load['details']:''?>"
                                       required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="text-center mt-5">
            </div>

            <div class="mt-5 text-right" style="margin-top: 100px;margin-bottom: 10px">
                <?php
                $isPermission=PermissionManager::checkaddRights($link1,$_SESSION['userid'],$_REQUEST['pid']);
                if($isPermission){
                    ?>
                <a href="create_form.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>&id=<?=$load['id']?>" class="btn btn-primary">Create form</a>
                <?php
                }
                ?>
            </div>

            <table width="95%" id="myacc-users-grid" class="display table-striped table table-hover" style="margin-top:4px;" align="center" cellpadding="4" cellspacing="0" border="1">
                <thead class="bg-primary">
                <tr>
                    <td>#</td>
                    <td>Form Name</td>
                    <td>Display Name</td>
                    <td>Status</td>
                    <td>Edit/View</td>
                </tr>
                </thead>
            </table>

            <div class="text-center">
                <span class="btn btn-primary" onclick="window.location.href='fms_master.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>'" style="text-transform: capitalize"><span id="operation_name">back</span></span>
            </div>
        </div>
    </div>
</div>
</div>

<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>

</body>
</html>
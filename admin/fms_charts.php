<?php
require_once("../includes/config.php");
global $link1;

$flag=$_REQUEST['flag']??'true';

set_exception_handler(function($e){
    $errormsg=$e->getMessage();
    header("Location: fms_master.php?{$errormsg}");
    exit();
});

$response=[];
$response['pid']=$_REQUEST['pid'];
$response['hid']=$_REQUEST['hid'];
$response['id']=$_REQUEST['id'];

if(isset($_REQUEST['id']) && $_REQUEST['id']!==""){
    $flag=true;
    $data=getFMsbyid($link1,$_REQUEST['id']);
}else{
    $response['flag']=false;
    $response['msg']='Id is meassing';
    $response['type']='error';
    $param=http_build_query($response);
    throw new Exception($param);
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
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/dataTables.responsive.css">
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../js/dataTables.responsive.min.js"></script>
    <title><?=siteTitle?></title>
    <?=
    ajaxCall('myacc-users-grid',
        '../pagination/chart-grid-data-view.php',
        ["pid"=>$_REQUEST['pid']??'',"hid"=>$_REQUEST['hid']??'',"fms_id"=>$_REQUEST['id']??''],
        'POST')
    ?>
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
<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <h2 align="center" style="font-style: italic;text-transform: capitalize"><i class="fa fa-users"></i> <?=$data['fmsname']?>'s Charts</h2>
            <span><?=$_SESSION['csrf_manu']?></span>

            <?php
            if(isset($_REQUEST['msg'])){?>
                <div id="errorPopup" class="toast" style="background-color: <?= isset($_REQUEST['type']) && $_REQUEST['type']==='error'?'darkred':'green' ?>">
                    <span class="icon">⚠️</span>
                    <span class="message"><?=htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8');?></span>
                </div>
            <?php } ?>
            <?php
            if($flag){
            ?>
                <div style="text-align: end">
                    <?php
                    $pid = $_REQUEST['pid'] ?? null;
                    $hid = $_REQUEST['hid'] ?? '';
                    $id=$data['id'] ?? 0;

                    if ($pid && PermissionManager::checkaddRights($link1,$_SESSION['userid'], $pid)) {
                        ?>
                        <button class="btn btn-primary"
                                onclick="window.location.href='gui_master_2.php?pid=<?=$pid?>&hid=<?=$hid?>&id=<?=$id?>'">
                            Add FMS
                        </button>
                        <?php
                    }
                    ?>
                </div>

                <form class="form-horizontal" role="form">
                    &nbsp;&nbsp;
                    <div class="form-group"  id="page-wrap" style="margin-left:10px;margin-right: 5px;"><br/><br/>

                        <table  width="95%" id="myacc-users-grid" class="display table-striped" align="center" cellpadding="4" cellspacing="0" border="1">
                            <thead>
                            <tr class="<?=$tableheadcolor?>">
                                <th>S.No</th>
                                <th>title</th>
                                <th>X-Axis Label</th>
                                <th>Y-Axis Label</th>
                                <th>X-Axis Param</th>
                                <th>Y-Axis Param</th>
                                <th>Status</th>
                                <th>View/Edit</th>

                            </tr>
                            </thead>
                        </table>

                        <div style="margin-top: 20px;text-align: center">
                            <a href="fms_master.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>
</div>
<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
<script>
    document.querySelectorAll("table td, table th").forEach((cell) => {
        cell.style.textTransform = "capitalize";
    });
</script>
</body>
</html>
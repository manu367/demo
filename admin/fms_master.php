<?php
require_once("../includes/config.php");
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
    <title><?=siteTitle?></title>
    <script>
        $(document).ready(function () {
            $('#myacc-users-grid').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "../pagination/fms-grid-data.php",
                    type: "POST",
                    data: {
                        pid: "<?= $_REQUEST['pid'] ?? '' ?>",
                        hid: "<?= $_REQUEST['hid'] ?? '' ?>"
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
            <h2 align="center" style="font-style: italic"><i class="fa fa-users"></i> FMS Master</h2>
            <span><?=$_SESSION['csrf_manu']?></span>
            <?php
            openssl_encrypt("this is mnu pathak")
            ?>
            <?php
            if(isset($_REQUEST['msg'])){?>
                <div id="errorPopup" class="toast" style="background-color: <?= isset($_REQUEST['type']) || $_REQUEST['type']==='error'?'darkred':'green' ?>">
                    <span class="icon">⚠️</span>
                    <span class="message"><?=htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8');?></span>
                </div>
            <?php } ?>
            <!--            pid and hid hidden form-->
            <form class="form-horizontal" role="form" name="form1" action="" method="get">
                <div class="form-group">
                    <div class="col-md-6"><label class="col-md-5 control-label"></label>
                        <div class="col-md-5">
                            <input name="pid" id="pid" type="hidden" value="<?=$_REQUEST['pid']?>"/>
                            <input name="hid" id="hid" type="hidden" value="<?=$_REQUEST['hid']?>"/>
                        </div>
                    </div>
                    <div class="col-md-6"><label class="col-md-5 control-label"></label>
                        <div class="col-md-5" align="left">
                        </div>
                    </div>
                </div>
            </form>
            <div style="text-align: end">
                <button
                    class="btn btn-primary"
                    onclick="window.location.href='add_fms_master.php?op=add&pid=<?=isset($_REQUEST['pid'])?>&hid=<?=isset($_REQUEST['hid'])?>'">
                    Add FMS
                </button>
            </div>
            <!--            main form for showing data-->
            <form class="form-horizontal" role="form">
                &nbsp;&nbsp;
                <div class="form-group"  id="page-wrap" style="margin-left:10px;"><br/><br/>

                    <table  width="100%" id="myacc-users-grid" class="display" align="center" cellpadding="4" cellspacing="0" border="1">
                        <thead>
                        <tr class="<?=$tableheadcolor?>">
                            <th>S.No</th>
                            <th>FMS Name</th>
                            <th>Details</th>
                            <th>create Date</th>
                            <th>update Date</th>
                            <th>Status</th>
                            <th>View/Edit</th>
                            <th>Create Form</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <!--</div>-->
            </form>
        </div>
    </div>
</div>
<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
</body>
</html>
<?php
$fun_id = array("a"=>array(107));
require_once("../includes/config.php");

$isPermission=PermissionManager::checkaddRights($link1,$_SESSION['userid'],$_REQUEST['pid']);
$pid=$_REQUEST['pid'];
$hid=$_REQUEST['hid'];
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
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	var dataTable = $('#emp-grid').DataTable( {
		"processing": true,
		"serverSide": true,
		"bStateSave": true,
		"ajax":{
			url :"../pagination/role-grid-data.php", // json datasource
			data: { "pid": "<?=$_REQUEST['pid']?>", "hid": "<?=$_REQUEST['hid']?>"},
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".emp-grid-error").html("");
				$("#emp-grid").append('<tbody class="emp-grid-error"><tr><th colspan="4">No data found in the server</th></tr></tbody>');
				$("#emp-grid_processing").css("display","none");
				
			}
		}
	} );
} );
</script>
<title><?=siteTitle?></title>
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
			 z-index: 999;
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
        <span class="icon">✔</span>
        <span class="message"><?=htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8');?></span>
    </div>
<?php } ?>

<div class="container-fluid">
	<div class="row content">
	<?php 
    include("../includes/leftnav2.php");
    ?>
    	<div class="<?=$screenwidth?> tab-pane fade in active" id="home">
      		<h2 align="center"><i class="fa fa-id-badge"></i> Role Master</h2>

            <div class="text-right">
                <?php
                if($isPermission){
                    echo '<a href="add_role.php?pid='.$pid.'&hid='.$hid.'" class="btn btn-primary">Add Role</a>';
                }
                ?>
            </div>

      		<form class="form-horizontal" role="form">
        		<div class="form-group"  id="page-wrap" style="margin-left:10px;"><br/>
       				<table  width="100%" id="emp-grid" class="display" align="center" cellpadding="4" cellspacing="0" border="1">
                    <thead>
                        <tr class="<?=$tableheadcolor?>">
                            <th>S.No</th>
                            <th>User Type</th>
                            <th>Type</th>
                            <th>Tab Rights</th>
                            <th>View/Edit</th>
                        </tr>
                    </thead>
                	</table>
                </div>
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
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
</head>
<body>
<?php if($_REQUEST['msg']){?><br>
    <h4 align="center" style="color:#FF0000"><?=$_REQUEST['msg']?></h4>
<?php }?>

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
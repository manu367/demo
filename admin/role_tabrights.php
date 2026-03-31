<?php
require_once("../includes/config.php");
global $link1;

$usertype = ($_REQUEST['id']);
$role_assign=new RoleAssienment($link1);

if(isset($_POST['role_permission'])){
    $role_assign->updatePermission($_POST);
}

?>
<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title><?=siteTitle?></title>
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
		$("#frm2").validate();
    });
	///// multiple check all function
 function checkFunc(field,ind,val){
	var chk=document.getElementById(val+""+ind).checked;
	if(chk==true){ checkAll(field); }
	else{ uncheckAll(field);}
 }
 function checkAll(field){
   for (i = 0; i < field.length; i++)
        field[i].checked = true ;
 }
 function uncheckAll(field){
   for (i = 0; i < field.length; i++)
        field[i].checked = false ;
 }
 </script>
 <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <style>
        .tab-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 per row */
            gap: 15px; /* row + column spacing */
        }

        .tab-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?>">
      		<h2 align="center"><i class="fa fa-users"></i> View/Edit Role</h2>
      		<h4 align="center"><?=$usertype?></h4>

      		<div class="form-group"  id="page-wrap" style="margin-left:10px;" >
      	 		<ul class="nav nav-tabs">
            		<li class="active"><a data-toggle="tab" href="#home"><i class="fa fa-id-card"></i> Master/Report Tab</a></li>
<!--            		<li><a data-toggle="tab" href="#menu1"><i class="fa fa-sitemap"></i> Process Tab</a></li>-->
          		</ul>
    	  		<div class="tab-content">
            		<div id="home" class="tab-pane fade in active"><br/>
              			<form  name="frm1" id="frm1" class="form-horizontal" action="" method="post">
                            <input type="hidden" name="role_id" value="<?=$usertype?>">
                            <input type="hidden" name="function_id" value="<?=$_REQUEST['pid']?>">
      						<?=$role_assign->printtabList($_REQUEST['id'])?>
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" name="role_permission" id="role_permission">Save</button>
                                <a href="role_master.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>" class="btn btn-primary">Cancel</a>
                            </div>
              			</form>
            		</div>
            		<div id="menu1" class="tab-pane fade">
              			<br/>
                        sdcsdc
            		</div>
				</div>
      		</div>
    	</div><!--End col-sm-9-->
	</div><!--End row content-->
</div><!--End container fluid-->
<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
</body>
</html>
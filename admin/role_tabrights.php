<?php
require_once("../includes/config.php");
global $link1;

$usertype = ($_REQUEST['id']);
$role_assign=new RoleAssienment($link1);
$flag=null;
if(isset($_POST['role_permission'])){
    $flag=$role_assign->updatePermission($_POST);
//    var_dump($flag);exit();
}

if (isset($flag) && $flag) {
    header("location: home2.php?pid=homeadmin&hid=home");
    exit();
}


//var_dump($flag);exit();
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
    <style  >
        .tab-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            padding: 10px;
        }

        .tab-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #ddd;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            line-height: 1.3;
        }

        /* Checkbox spacing */
        .tab-item input[type="checkbox"] {
            transform: scale(1.2);
            cursor: pointer;
        }
        /* Icon styling */
        .tab-item i {
            font-size: 16px;
            min-width: 20px;
            text-align: center;
        }
        /* Hover vibe */
        .tab-item:hover {
            border-color: #007bff;
            background: #f0f7ff;
        }
        /* Checked state */
        .tab-item input:checked + i {
            color: #007bff;
        }

        .tab-item input:checked ~ span {
            font-weight: 600;
        }

        /* Mobile tuning */
        @media (max-width: 600px) {
            .tab-grid {
                grid-template-columns: 1fr;
            }

            .tab-item {
                padding: 14px;
                font-size: 15px;
            }
        }

        /* Tablet tuning */
        @media (min-width: 601px) and (max-width: 992px) {
            .tab-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Desktop */
        @media (min-width: 993px) {
            .tab-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
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
if($flag){?>
    <div id="errorPopup" class="toast" style="background-color: green">
        <span class="message">Permission are Updated</span>
    </div>
<?php } ?>

<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?>">
      		<h2 align="center"><i class="fa fa-users"></i> View/Edit Role</h2>
      		<h4 align="center"><?=isset($_REQUEST['user'])?htmlspecialchars($_REQUEST['user'],ENT_IGNORE):''?></h4>

      		<div class="form-group"  id="page-wrap" style="margin-left:10px;" >
      	 		<ul class="nav nav-tabs">
            		<li class="active"><a data-toggle="tab" href="#home"><i class="fa fa-id-card"></i> Roles/Permissions</a></li>
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

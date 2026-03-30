<?php
require_once("../includes/config.php");
global $link1;

set_exception_handler(function ($exception) {
    if($exception instanceof GlobalException){
        header('location:update_permission_3.php?'.$exception->getMessage());
        exit();
    }
});




$userid=isset($_REQUEST['userid'])?$_REQUEST['userid']:$_SESSION['userid'];
$updatepermission=new UpdatePermission();
$updatepermission->setConnection($link1);
$updatepermission->setUserid($userid);

$userid=isset($_REQUEST['userid'])?$_REQUEST['userid']:'';
$utype=isset($_REQUEST['utype'])?$_REQUEST['utype']:'';
$u_name=isset($_REQUEST['u_name'])?$_REQUEST['u_name']:'';

$page=isset($_REQUEST['page'])?$_REQUEST['page']:'';
$srch=isset($_REQUEST['srch'])?$_REQUEST['srch']:'';
$pid=isset($_REQUEST['pid'])?$_REQUEST['pid']:'';
$hid=isset($_REQUEST['hid'])?$_REQUEST['hid']:'';

if ($_POST['update_home']) {

    try {
         $isupdate = $updatepermission->updatePermission($_POST);
//        $isupdate = false; // testing

        $data = [
                'userid' => $userid,
                'utype'  => $utype,
                'u_name' => $u_name,
                'page'   => $page,
                'srch'   => $srch,
                'pid'    => $pid,
                'hid'    => $hid
        ];

        if ($isupdate) {
            $data['type'] = 'success';
            $data['msg']  = 'User Updated Successfully';
        } else {
            $data['type'] = 'error';
            $data['msg']  = 'Permission not updated';
        }

        $params = http_build_query($data);
        header("Location: update_permission_3.php?$params");
        exit;

    } catch (Exception $e) {

        $data = [
                'userid' => $userid,
                'utype'  => $utype,
                'u_name' => $u_name,
                'page'   => $page,
                'srch'   => $srch,
                'pid'    => $pid,
                'hid'    => $hid,
                'type'   => 'error',
                'msg'    => $e->getMessage()
        ];

        $params = http_build_query($data);
        header("Location: update_permission_3.php?$params");
        exit;
    }
}

if($_POST['update_fms_permission']){
    $flag=flase;
    $updatepermission->resentAllFMSPermission($_SESSION['userid']);
    for($i=0;$i<count($_POST['fms']);$i++){
        $flag=$updatepermission->updateFMSPermission($_POST['fms'][$i],$_SESSION['userid']);
    }
    $data = [
            'userid' => $userid,
            'utype'  => $utype,
            'u_name' => $u_name,
            'page'   => $page,
            'srch'   => $srch,
            'pid'    => $pid,
            'hid'    => $hid
    ];
    if($flag){
        $data['type'] = 'success';
        $data['msg']  = 'FMS Updated Successfully';
        $params = http_build_query($data);
        header("Location: update_permission_3.php?$params");
    }else{
        $data['type'] = 'error';
        $data['msg']  = 'Some things is Wrong';
        $params = http_build_query($data);
        header("Location: update_permission_3.php?$params");
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=siteTitle?></title>
    <script src="../js/jquery.js"></script>
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/abc.css" rel="stylesheet">
    <script src="../js/bootstrap.min.js"></script>
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script>
        function checkAll(field){
            for (i = 0; i < field.length; i++)
                field[i].checked = true ;
        }
        function uncheckAll(field){
            for (i = 0; i < field.length; i++)
                field[i].checked = false ;
        }

        function checkFunc(field,ind,val){
            var chk=document.getElementById(val+""+ind).checked;
            if(chk==true){
                checkAll(field);
            }
            else{
                uncheckAll(field);
            }
        }

        ////check TAB click so we can check and uncheck the operation right
        function checkTabClick(val,field){
            var chk=val.checked;
            if(chk==true){ checkAll(field); }
            else{uncheckAll(field);}
        }
    </script>
    <script>
    $(document).ready(function() {
        if (location.hash) {
            $("a[href='" + location.hash + "']").tab("show");
        }
        $(document.body).on("click", "a[data-toggle]", function(event) {
            location.hash = this.getAttribute("href");
        });
    });

    $(window).on("popstate", function() {
        var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
        $("a[href='" + anchor + "']").tab("show");
        if(location.hash=="#menu1"){
            document.getElementById("home").style.display="none";
            document.getElementById("menu1").style.display="";
            document.getElementById("menu2").style.display="none";
            document.getElementById("menu3").style.display="none";
            document.getElementById("menu4").style.display="none";
            document.getElementById("menu5").style.display="none";
        }
        else if(location.hash=="#menu2"){
            document.getElementById("home").style.display="none";
            document.getElementById("menu1").style.display="none";
            document.getElementById("menu2").style.display="";
            document.getElementById("menu3").style.display="none";
            document.getElementById("menu4").style.display="none";
            document.getElementById("menu5").style.display="none";
        }
        else if(location.hash=="#menu3"){
            document.getElementById("home").style.display="none";
            document.getElementById("menu1").style.display="none";
            document.getElementById("menu2").style.display="none";
            document.getElementById("menu3").style.display="";
            document.getElementById("menu4").style.display="none";
            document.getElementById("menu5").style.display="none";
        }
        else if(location.hash=="#menu4"){
		document.getElementById("home").style.display="none";
		document.getElementById("menu1").style.display="none";
		document.getElementById("menu2").style.display="none";
		document.getElementById("menu3").style.display="none";
		document.getElementById("menu4").style.display="";
		document.getElementById("menu5").style.display="none";
	}
	else if(location.hash=="#menu5"){
		document.getElementById("home").style.display="none";
		document.getElementById("menu1").style.display="none";
		document.getElementById("menu2").style.display="none";
		document.getElementById("menu3").style.display="none";
		document.getElementById("menu4").style.display="none";
		document.getElementById("menu5").style.display="";
	}
	else{
		document.getElementById("home").style.display="";
		document.getElementById("menu1").style.display="none";
		document.getElementById("menu2").style.display="none";
		document.getElementById("menu3").style.display="none";
		document.getElementById("menu4").style.display="none";
		//document.getElementById("menu5").style.display="none";
	}
});
////// get city on the basis of state selection
function getCity(stateid){
	var state_id = stateid.split("~");
	  $.ajax({
	    type:'post',
		url:'../includes/getAzaxFields.php',
		data:{permission_state:state_id[0],usrid:'<?php if(isset($_REQUEST['userid'])){ echo $_REQUEST['userid'];}?>'},
		success:function(data){
	    	$('#disp_city').html(data);
		}
	  });
}
</script>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 28px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #4CAF50;
        }

        input:checked + .slider:before {
            transform: translateX(22px);
        }

        .switch {
            touch-action: manipulation;
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
    <div id="errorPopup" class="toast" style="z-index: 9999;background-color: <?= isset($_REQUEST['type']) && $_REQUEST['type']==='error'?'darkred':'green' ?>">
        <span class="icon">⚠️</span>
        <span class="message"><?=htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8');?></span>
    </div>
<?php } ?>

<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?>">
            <h2 align="center"><i class="fa <?=$fa_icon?>"></i> Update User Permission</h2>
            <h4 align="center">
                <?=$_REQUEST['u_name']."  (".$_REQUEST['userid'].")";?>
            </h4>

            <div class="form-group"  id="page-wrap" style="margin-left:10px;">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home"><i class="fa fa-database fa-lg"></i>&nbsp;&nbsp;Masters / Reports</a></li>
                    <li><a data-toggle="tab" href="#menu1"><i class="fa fa-cogs fa-lg"></i>&nbsp;&nbsp;Region</a></li>
                    <li><a data-toggle="tab" href="#menu2"><i class="fa fa-university fa-lg"></i>&nbsp;&nbsp;Location</a></li>
                    <li><a data-toggle="tab" href="#menu4"><i class="fa fa-suitcase fa-lg"></i>&nbsp;&nbsp;Product Category</a></li>
                    <li><a data-toggle="tab" href="#menu5"><i class="fa fa-suitcase fa-lg"></i>&nbsp;&nbsp;FMS</a></li>
                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <form name="frm" class="form-horizontal" action="" method="post">
<!--                            <label class="switch">-->
<!--                                <input type="checkbox" name="check_box_manu" value="2">-->
<!--                                <span class="slider"></span>-->
<!--                            </label>-->
                            <div class="table-responsive">
                                <table id="myTable1" class="table table-hover">
                                    <thead>
                                    <tr class="<?=$tableheadcolor?>">
                                        <td width="20%">Tab Name</td>
                                        <td width="10%"><i class='fa fa-plus'></i>&nbsp;Add</td>
                                        <td width="10%"><i class='fa fa-edit'></i>&nbsp;Edit</td>
                                        <td width="10%"><i class='fa fa-eye'></i>&nbsp;View</td>
                                        <td width="10%"><i class='fa fa-remove'></i>&nbsp;Cancel</td>
                                        <td width="10%"><i class='fa fa-print'></i>&nbsp;Print</td>
                                        <td width="10%"><i class='fa fa-file-excel-o'></i>&nbsp;Excel Export</td>
                                        <td width="10%"><i class='fa fa-legal'></i>&nbsp;Approval</td>
                                        <td width="10%"><i class='fa fa-ban'></i>&nbsp;Block Price Display</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?= $updatepermission->printMainTabName()?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-buttons" align="center">
                                <button title="Previous" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='#menu3'">Previous</button>
                                <input type="submit" class="btn<?=$btncolor?>" name="update_home" id="submitTab4" value="Save"/>
                                <button title="Back" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='addAdminUser.php?op=edit&id=<?php echo $_REQUEST['userid'];?>&srch=<?php if(isset($_REQUEST['srch'])){ echo $_REQUEST['srch'];}?>&status=<?php if(isset($_REQUEST['status'])){ echo $_REQUEST['status'];}?><?=$pagenav?>'"><i class="fa fa-reply fa-lg"></i>&nbsp;&nbsp;Back</button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab 2 Region Rights-->
                    <div id="menu1" class="tab-pane fade" >
                        this for region tab
                    </div>

                    <!-- Tab 3 Location Rights-->
                    <div id="menu2" class="tab-pane fade">
                        this is location page
                    </div>
            
            <!-- Tab 4 Process Skill Rights-->
                    <div id="menu3" class="tab-pane fade">
                        process page
                    </div>

           <!-- Tab 5 product category Rights-->
                    <div id="menu4" class="tab-pane fade">
                        catehry page
                    </div>

                    <div id="menu5" class="tab-pane fade">
                        <form id="frm4" name="frm4" class="form-horizontal" action="" method="post">
                            <div class="table-responsive">
                                <?=$updatepermission->printfmsName()?>
                            </div>
                            <div class="form-buttons" align="center">
                                <button title="Previous" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='#menu3'">Previous</button>
                                <input type="submit" class="btn<?=$btncolor?>" name="update_fms_permission" id="submitTab4" value="Save"/>
                                <button title="Back" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='addAdminUser.php?op=edit&id=<?php echo $_REQUEST['userid'];?>&srch=<?php if(isset($_REQUEST['srch'])){ echo $_REQUEST['srch'];}?>&status=<?php if(isset($_REQUEST['status'])){ echo $_REQUEST['status'];}?><?=$pagenav?>'"><i class="fa fa-reply fa-lg"></i>&nbsp;&nbsp;Back</button>
                            </div>
                        </form>
                    </div>
            
            
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

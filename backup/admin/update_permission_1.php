<?php 
////// Function ID ///////
$fun_id = array("a"=>array(1));
//////////////////////////
require_once("../includes/config.php");
////// Access check //////
//if(!access_check_v3($link1, $fun_id, $_SESSION["userid"], $_SESSION["utype"])){exit;}
//////////////////////////
@extract($_POST);
############# if form 2 is submitted #################
if(isset($_POST['submitTab'])){
	// Update Function Rights
	mysqli_query($link1,"update access_tab set status='0' where userid='".$_REQUEST['userid']."'")or die(mysqli_error($link1));
	mysqli_query($link1,"update operation_rights set 
                            add_rgt='',edit_rgt='', view_rgt='', cancel_rgt='', print_rgt='', download_rgt='' ,approval_rgt='', block_price='' where userid='".$_REQUEST['userid']."'")or die(mysqli_error($link1));
		$rrr="report";
		$addrgt="add_rgt";
		$editrgt="edit_rgt";
		$viewrgt="view_rgt";
		$cancelrgt="cancel_rgt";
		$printrgt="print_rgt";
		$excelrgt="excel_rgt";
		$apprgt="app_rgt";
		$pricergt="price_rgt";
		$arr_addrgt = $_REQUEST[$addrgt];
		$arr_editrgt = $_REQUEST[$editrgt];
		$arr_viewrgt = $_REQUEST[$viewrgt];
		$arr_cancelrgt = $_REQUEST[$cancelrgt];
		$arr_printrgt = $_REQUEST[$printrgt];
		$arr_excelrgt = $_REQUEST[$excelrgt];
		$arr_apprgt = $_REQUEST[$apprgt];
		$arr_pricergt = $_REQUEST[$pricergt];
		$rep1 = $_REQUEST[$rrr];
		$count=count($_REQUEST[$rrr]);
    	//print_r($arr_addrgt);
		$j=0;
		while($j < $count){
			//echo $arr_addrgt[$rep1[$j]]."-".$arr_editrgt[$rep1[$j]]."-".$arr_viewrgt[$rep1[$j]]."-".$arr_cancelrgt[$rep1[$j]]."-".$arr_printrgt[$rep1[$j]]."-".$arr_excelrgt[$rep1[$j]]."<br/>";
			 if($rep1[$j]==''){
				$newstatus=0;
			 }else{
				$newstatus=1;
			 }
			 if(!empty($arr_addrgt[$rep1[$j]])){ if($arr_addrgt[$rep1[$j]]!=$rep1[$j]){ $add_status = "N";}else{ $add_status = "Y";}}else{ $add_status = "N";}
			 if(!empty($arr_editrgt[$rep1[$j]])){ if($arr_editrgt[$rep1[$j]]!=$rep1[$j]){ $edit_status = "N";}else{ $edit_status = "Y";}}else{ $edit_status = "N";}
			 if(!empty($arr_viewrgt[$rep1[$j]])){ if($arr_viewrgt[$rep1[$j]]!=$rep1[$j]){ $view_status = "N";}else{ $view_status = "Y";}}else{ $view_status = "N";}
			 if(!empty($arr_cancelrgt[$rep1[$j]])){ if($arr_cancelrgt[$rep1[$j]]!=$rep1[$j]){ $cancel_status = "N";}else{ $cancel_status = "Y";}}else{ $cancel_status = "N";}
			 if(!empty($arr_printrgt[$rep1[$j]])){ if($arr_printrgt[$rep1[$j]]!=$rep1[$j]){ $print_status = "N";}else{ $print_status = "Y";}}else{ $print_status = "N";}
			 if(!empty($arr_excelrgt[$rep1[$j]])){ if($arr_excelrgt[$rep1[$j]]!=$rep1[$j]){ $excel_status = "N";}else{ $excel_status = "Y";}}else{ $excel_status = "N";}
			 if(!empty($arr_apprgt[$rep1[$j]])){ if($arr_apprgt[$rep1[$j]]!=$rep1[$j]){ $app_status = "N";}else{ $app_status = "Y";}}else{ $app_status = "N";}
			 if(!empty($arr_pricergt[$rep1[$j]])){ if($arr_pricergt[$rep1[$j]]!=$rep1[$j]){ $price_status = "N";}else{ $price_status = "Y";}}else{ $price_status = "N";}
			 // alrady exist
			 if(mysqli_num_rows(mysqli_query($link1,"select tabid from access_tab where userid='".$_REQUEST['userid']."' and tabid='".$rep1[$j]."'"))>0){
				mysqli_query($link1,"update access_tab set status='".$newstatus."' where userid='".$_REQUEST['userid']."' and tabid='".$rep1[$j]."'")or die(mysqli_error($link1));
			 }else{
				mysqli_query($link1,"insert into access_tab set userid='".$_REQUEST['userid']."' ,tabid='".$rep1[$j]."',status='".$newstatus."'")or die(mysqli_error($link1));
			 }
			 // alrady exist
			 if(mysqli_num_rows(mysqli_query($link1,"select id from operation_rights where userid='".$_REQUEST['userid']."' and tabid='".$rep1[$j]."'"))>0){
				mysqli_query($link1,"update operation_rights set add_rgt='".$add_status."',edit_rgt='".$edit_status."', view_rgt='".$view_status."', cancel_rgt='".$cancel_status."', print_rgt='".$print_status."', download_rgt='".$excel_status."',approval_rgt='".$app_status."',block_price='".$price_status."' where userid='".$_REQUEST['userid']."' and tabid='".$rep1[$j]."'")or die(mysqli_error($link1));
			 }else{
				mysqli_query($link1,"insert into operation_rights set userid='".$_REQUEST['userid']."' ,tabid='".$rep1[$j]."',add_rgt='".$add_status."',edit_rgt='".$edit_status."', view_rgt='".$view_status."', cancel_rgt='".$cancel_status."', print_rgt='".$print_status."', download_rgt='".$excel_status."',approval_rgt='".$app_status."',block_price='".$price_status."'")or die(mysqli_error($link1));
			 }
		   $j++;
		}
	// end Function Rights
	dailyActivity($_REQUEST['userid'], $_REQUEST['userid'], "OPR USER RIGHT", "UPDATE", $ip, $link1, "");
}
else if(isset($_POST['submitTab1'])){
	/////// map city/state
	$post_state = explode("~",$_POST['state_name']);
	$res_upd = mysqli_query($link1,"update access_region set status='' where userid='".$_REQUEST['userid']."' and stateid='".$post_state[0]."' and zoneid='".$post_state[1]."'");
	$post_regiondata = $_POST['report1'];
	$count_region = count($post_regiondata);
	$i=0;
	while($i < $count_region){
		if($post_regiondata[$i]==''){
			$newstatus = "";
		}else{
			$newstatus = "Y";
		}
		// alrady exist
		if(mysqli_num_rows(mysqli_query($link1,"select id from access_region where userid='".$_REQUEST['userid']."' and cityid='".$post_regiondata[$i]."'"))>0){
			$res_mapupd = mysqli_query($link1,"update access_region set status='".$newstatus."' where userid='".$_REQUEST['userid']."' and cityid='".$post_regiondata[$i]."'");
		}else{
			$res_mapupd = mysqli_query($link1,"insert into access_region set userid='".$_REQUEST['userid']."', cityid='".$post_regiondata[$i]."',stateid='".$post_state[0]."',zoneid='".$post_state[1]."', status='".$newstatus."'");
		}
		$i++;
	}
	dailyActivity($_REQUEST['userid'], $_REQUEST['userid'], "CITY USER RIGHT", "UPDATE", $ip, $link1, "");
}
else if(isset($_POST['submitTab2'])){
	/////// map locations
	mysqli_query($link1,"update access_location set status='0' where userid='".$_REQUEST['userid']."'");
	$post_locdata = $_POST['report2'];
	$count_loc = count($post_locdata);
	$i=0;
	while($i < $count_loc){
		if($post_locdata[$i]==''){
			$newstatus = "0";
		}else{
			$newstatus = "1";
		}
		// alrady exist
		if(mysqli_num_rows(mysqli_query($link1,"select id from access_location where userid='".$_REQUEST['userid']."' and location_code='".$post_locdata[$i]."'"))>0){
			$res_mapupd = mysqli_query($link1,"update access_location set status='".$newstatus."' where userid='".$_REQUEST['userid']."' and location_code='".$post_locdata[$i]."'");
		}else{
			$res_mapupd = mysqli_query($link1,"insert into access_location set userid='".$_REQUEST['userid']."', location_code='".$post_locdata[$i]."', status='".$newstatus."'");
		}
		$i++;
	}
	dailyActivity($_REQUEST['userid'], $_REQUEST['userid'], "LOCATION USER RIGHT", "UPDATE", $ip, $link1, "");
}
else if(isset($_POST['submitTab3'])){
		// Update work Rights
		$rrr="skill";
		mysqli_query($link1,"UPDATE admin_users SET process_ids='".implode(',',$_REQUEST[$rrr])."' WHERE username='".$_REQUEST['userid']."'")or die(mysqli_error($link1));
		dailyActivity($_REQUEST['userid'], $_REQUEST['userid'], "SKILL USER RIGHT", "UPDATE", $ip, $link1, "");
	}
else if(isset($_POST['submitTab4'])){//// update product sub cat
	mysqli_query($link1,"update mapped_productcat set status='' WHERE userid='".$_REQUEST['userid']."' ")or die(mysqli_error($link1));
	for($s=0;$s<$_REQUEST['count_repTab4'];$s++){
		$rrr="report14".$s;
		$rep1=$_REQUEST[$rrr];
		//$rep2=$_REQUEST[$rrr2];
		//$product_cat=$_REQUEST[$prdcat];
		$count=count($_REQUEST[$rrr]);
		$j=0;
		  while($j < $count){
			 if($rep1[$j]==''){
				$status1='';
			 }else{
				$status1='Y';
			 }
			 // alrady exist
			 $prodsbct=mysqli_fetch_assoc(mysqli_query($link1,"select prod_sub_cat,product_category from product_sub_category where psubcatid='".$rep1[$j]."'")); 
			 //$serflag=mysqli_fetch_assoc(mysqli_query($link1,"select id,service_flag from mapped_productcat where userid='$_REQUEST[userid]' and prod_subcatid='$rep1[$j]' and product_cat='$prodsbct[prod_cat_type]'"));
             if(mysqli_num_rows(mysqli_query($link1,"select id from mapped_productcat where userid='".$_REQUEST['userid']."' and prod_subcatid='".$rep1[$j]."' and product_cat='".$prodsbct['product_category']."'"))>0){
				mysqli_query($link1,"update mapped_productcat set status='".$status1."' where userid='".$_REQUEST['userid']."' and prod_subcatid='".$rep1[$j]."' and product_cat='".$prodsbct['product_category']."'")or die(mysqli_error($link1));
			 }else{
				mysqli_query($link1,"insert into mapped_productcat set userid='".$_REQUEST['userid']."',product_subcat='".$prodsbct['prod_sub_cat']."',prod_subcatid='".$rep1[$j]."',product_cat='".$prodsbct['product_category']."',status='".$status1."'")or die(mysqli_error($link1));
			 }
		   $j++;
		  }
		  $count=0;
	}
}	
else{
}
///// get user basic details
$res_user = mysqli_query($link1,"SELECT process_ids FROM admin_users WHERE username='".$_REQUEST['userid']."'");
$row_user = mysqli_fetch_assoc($res_user);
///// price block option array
$arr_price_opt = array("16","17","18","20","21","26","28","30","34","39","45","53","54","56","57");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
<?=siteTitle?>
</title>
<script src="../js/jquery.js"></script>
<?php 
 include("../includes/fontawesome.html");
 include("../includes/main_css.html"); 
 include("../includes/bootstrap.html");
 include("../includes/datatables.html");
 ?>
<script>
 function checkAll(field){
   for (i = 0; i < field.length; i++)
        field[i].checked = true ;
 }
 function uncheckAll(field){
   for (i = 0; i < field.length; i++)
        field[i].checked = false ;
 }
 ///// multiple check all function
 function checkFunc(field,ind,val){
	 //alert(field+"--"+ind+"--"+val);
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
		//document.getElementById("menu5").style.display="none";
	}
	else if(location.hash=="#menu2"){
		document.getElementById("home").style.display="none";
		document.getElementById("menu1").style.display="none";
		document.getElementById("menu2").style.display="";
		document.getElementById("menu3").style.display="none";
		document.getElementById("menu4").style.display="none";
		//document.getElementById("menu5").style.display="none";
	}
	else if(location.hash=="#menu3"){
		document.getElementById("home").style.display="none";
		document.getElementById("menu1").style.display="none";
		document.getElementById("menu2").style.display="none";
		document.getElementById("menu3").style.display="";
		document.getElementById("menu4").style.display="none";
		//document.getElementById("menu5").style.display="none";
	}
	else if(location.hash=="#menu4"){
		document.getElementById("home").style.display="none";
		document.getElementById("menu1").style.display="none";
		document.getElementById("menu2").style.display="none";
		document.getElementById("menu3").style.display="none";
		document.getElementById("menu4").style.display="";
		//document.getElementById("menu5").style.display="none";
	}
	/*else if(location.hash=="#menu5"){
		document.getElementById("home").style.display="none";
		document.getElementById("menu1").style.display="none";
		document.getElementById("menu2").style.display="none";
		document.getElementById("menu3").style.display="none";
		document.getElementById("menu4").style.display="none";
		document.getElementById("menu5").style.display="";
	}*/
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
</head>

<body>
<div class="container-fluid">
  <div class="row content">
    <?php 
    include("../includes/leftnav_admin.php");
    ?>
    <div class="<?=$screenwidth?>">
      <h2 align="center"><i class="fa <?=$fa_icon?>"></i> Update User Permission</h2>
      <h4 align="center">
        <?=$_REQUEST['u_name']."  (".$_REQUEST['userid'].")";?>
        <?php if(isset($_POST['submitTab'])=='Save' || isset($_POST['submitTab1'])=='Save' || isset($_POST['submitTab2'])=='Save' || isset($_POST['submitTab3'])=='Save' || isset($_POST['submitTab4'])=='Save'){ ?>
        <br/>
        <span style="color:#FF0000">
        <?php if(isset($_POST['submitTab'])=="Save"){ echo "Master/Reports Tab";}else if(isset($_POST['submitTab1'])=="Save"){echo "Region Tab";}else if(isset($_POST['submitTab2'])=="Save"){echo "Location Tab";}else if(isset($_POST['submitTab3'])=="Save"){echo "Skill Tab";}else if(isset($_POST['submitTab4'])=="Save"){echo "Product Category Tab";}else{} ?>
        permissions are updated.</span>
        <?php } ?>
      </h4>
      <div class="form-group"  id="page-wrap" style="margin-left:10px;">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home"><i class="fa fa-database fa-lg"></i>&nbsp;&nbsp;Masters / Reports</a></li>
          <li><a data-toggle="tab" href="#menu1"><i class="fa fa-cogs fa-lg"></i>&nbsp;&nbsp;Region</a></li>
          <li><a data-toggle="tab" href="#menu2"><i class="fa fa-university fa-lg"></i>&nbsp;&nbsp;Location</a></li>
          <li><a data-toggle="tab" href="#menu3"><i class="fa fa-microchip fa-lg"></i>&nbsp;&nbsp;Process Skill Mapping</a></li>
          <li><a data-toggle="tab" href="#menu4"><i class="fa fa-suitcase fa-lg"></i>&nbsp;&nbsp;Product Category</a></li>
        </ul>
        <div class="tab-content"> 
          <!-- Tab 1 Master / Region Rights-->
          <div id="home" class="tab-pane fade in active">
            <form id="frm" name="frm" class="form-horizontal" action="" method="post">
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
                  <?php 
				$rs=mysqli_query($link1,"select maintabname,maintabicon from tab_master where status='1' and tabfor='admin' group by maintabname order by maintabseq");
                $num=mysqli_num_rows($rs);
                if($num > 0){
                   $j=1;
                   while($row=mysqli_fetch_array($rs)){
                ?>
                    <tr>
                      <td style="border:none" class="bg-success"><i class="fa <?=$row['maintabicon']?> fa-lg"></i>&nbsp;<?=$row['maintabname']?></td>
                    </tr>
                    <?php 
				   $i=1;
				   $report="select tabid, subtabname,subtabicon from tab_master where maintabname='".$row['maintabname']."' and status='1' and tabfor='admin' order by subtabname";
                   $rs_report=mysqli_query($link1,$report) or die(mysqli_error($link1));
                   while($row_report=mysqli_fetch_array($rs_report)){?>
                    <tr>
                      <?php
                    $state_acc=mysqli_query($link1,"select tabid from access_tab where status='1' and tabid='".$row_report['tabid']."' and userid='".$_REQUEST['userid']."'")or die(mysqli_error());
                    $num1=mysqli_num_rows($state_acc);
                    ///// check operation rights
                    $res_oprrgt=mysqli_query($link1,"select * from operation_rights where tabid='".$row_report['tabid']."' and userid='".$_REQUEST['userid']."'")or die(mysqli_error());
                    $row_oprrgt=mysqli_fetch_array($res_oprrgt);
                        $line_seq = $j."_".$i;
                        ?>
                      <td><input style="width:20px"  type="checkbox" id="report<?=$j?>" name="report[]" value="<?=$row_report['tabid']?>" <?php if($num1 > 0) echo "checked";?> onClick="checkTabClick(this,document.frm.opr_rgt<?=$line_seq?>);" title="Tab Name"/>&nbsp;<i class="fa <?=$row_report['subtabicon']?> fa-lg"></i>&nbsp;<?=$row_report['subtabname']?></td>
                      <td><input style="width:20px"  type="checkbox" id="opr_rgt<?=$line_seq?>" name="add_rgt[<?=$row_report['tabid']?>]" value="<?=$row_report['tabid']?>" <?php if($row_oprrgt['add_rgt'] == "Y") echo "checked";?> title="Add Right"/></td>
                      <td><input style="width:20px"  type="checkbox" id="opr_rgt<?=$line_seq?>" name="edit_rgt[<?=$row_report['tabid']?>]" value="<?=$row_report['tabid']?>" <?php if($row_oprrgt['edit_rgt'] == "Y") echo "checked";?> title="Edit Right"/></td>
                      <td><input style="width:20px"  type="checkbox" id="opr_rgt<?=$line_seq?>" name="view_rgt[<?=$row_report['tabid']?>]" value="<?=$row_report['tabid']?>" <?php if($row_oprrgt['view_rgt'] == "Y") echo "checked";?> title="View Right"/></td>
                      <td><input style="width:20px"  type="checkbox" id="opr_rgt<?=$line_seq?>" name="cancel_rgt[<?=$row_report['tabid']?>]" value="<?=$row_report['tabid']?>" <?php if($row_oprrgt['cancel_rgt'] == "Y") echo "checked";?> title="Cancel Right"/></td>
                      <td><input style="width:20px"  type="checkbox" id="opr_rgt<?=$line_seq?>" name="print_rgt[<?=$row_report['tabid']?>]" value="<?=$row_report['tabid']?>" <?php if($row_oprrgt['print_rgt'] == "Y") echo "checked";?> title="Print Right"/></td>
                      <td><input style="width:20px"  type="checkbox" id="opr_rgt<?=$line_seq?>" name="excel_rgt[<?=$row_report['tabid']?>]" value="<?=$row_report['tabid']?>" <?php if($row_oprrgt['download_rgt'] == "Y") echo "checked";?> title="Excel Right"/></td>
                      <td><?php if($row_report['apply_approval']=="Y"){?><input style="width:20px"  type="checkbox" id="opr_rgt<?=$line_seq?>" name="app_rgt[<?=$row_report['tabid']?>]" value="<?=$row_report['tabid']?>" <?php if($row_oprrgt['approval_rgt'] == "Y") echo "checked";?> title="Approval Right"/><?php }?></td>
                      <td>
                      <?php if (in_array($row_report['tabid'], $arr_price_opt)){ ?>
                      <input style="width:20px"  type="checkbox" id="opr_rgt<?=$line_seq?>" name="price_rgt[<?=$row_report['tabid']?>]" value="<?=$row_report['tabid']?>" <?php if($row_oprrgt['block_price'] == "Y") echo "checked";?> title="Block Price Display"/>
                      <?php }?>
                      </td>
                    </tr>
                    <?php
						$i++;
                    }////// Close 2nd While Loop of TAB 2
                    ?>
                    <?php 
                       $j++;
				   }  
				}?>
                  </tbody>
                </table>
              </div>
              <div class="form-buttons" align="center">
                <button class='btn<?=$btncolor?>' id="submitTab" type="submit" name="submitTab" value="Save"><i class="fa fa-save fa-lg"></i>&nbsp;&nbsp;Save</button>
                <button title="Next" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='#menu1'">Next&nbsp;&nbsp;<i class="fa fa-forward fa-lg"></i></button>
                <button title="Back" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='addAdminUser.php?op=edit&id=<?php echo $_REQUEST['userid'];?>&srch=<?php if(isset($_REQUEST['srch'])){ echo $_REQUEST['srch'];}?>&status=<?php if(isset($_REQUEST['status'])){ echo $_REQUEST['status'];}?><?=$pagenav?>'"><i class="fa fa-reply fa-lg"></i>&nbsp;&nbsp;Back</button>
              </div>
            </form>
          </div>
          <!-- Tab 2 Region Rights-->
          <div id="menu1" class="tab-pane fade" >
            <form id="frm1" name="frm1" class="form-horizontal" action="" method="post">
              <div class="table-responsive">
                <table id="myTable2" class="table table-hover">
                  <thead>
                    <tr>
                      <td style="border:none"><strong>State:</strong>
                        <select name="state_name" id="state_name" class="form-control custom-select" style="width:250px;" onChange="getCity(this.value);">
                          <option value="">--Select State--</option>
                          <?php 
							$rs2=mysqli_query($link1,"SELECT * FROM state_master ORDER BY state");
                			while($row=mysqli_fetch_array($rs2)){
                			?>
                          <option value="<?=$row['stateid']."~".$row['zoneid']?>">
                          <?=$row['state']?>
                          </option>
                          <?php
							}
							?>
                        </select></td>
                    </tr>
                  </thead>
                </table>
              <span id="disp_city"></span> 
              </div>
              <div class="form-buttons" align="center">
                <button title="Previous" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='#home'"><i class="fa fa-backward fa-lg"></i>&nbsp;&nbsp;Previous</button>
                <button class='btn<?=$btncolor?>' id="submitTab1" type="submit" name="submitTab1" value="Save"><i class="fa fa-save fa-lg"></i>&nbsp;&nbsp;Save</button>
                <button title="Next" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='#menu2'">Next&nbsp;&nbsp;<i class="fa fa-forward fa-lg"></i></button>
                <button title="Back" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='addAdminUser.php?op=edit&id=<?php echo $_REQUEST['userid'];?>&srch=<?php if(isset($_REQUEST['srch'])){ echo $_REQUEST['srch'];}?>&status=<?php if(isset($_REQUEST['status'])){ echo $_REQUEST['status'];}?><?=$pagenav?>'"><i class="fa fa-reply fa-lg"></i>&nbsp;&nbsp;Back</button>
              </div>
            </form>
          </div>
          <!-- Tab 3 Location Rights-->
          <div id="menu2" class="tab-pane fade">
            <form id="frm2" name="frm2" class="form-horizontal" action="" method="post">
              <div class="table-responsive">
                <div class="form-buttons" style="float:right">
                  <input name="CheckAll" type="button" class="btn<?=$btncolor?>" onClick="checkAll(document.frm2.report2)" value="Check All" />
                  <input name="UnCheckAll" type="button" class="btn<?=$btncolor?>" onClick="uncheckAll(document.frm2.report2)" value="Uncheck All" />
                </div>
                <table id="myTable3" class="table table-hover">
                  <tbody>
                  	<?php 
					$res_loc = mysqli_query($link1,"select * from location_type_master");
					$num_loc = mysqli_num_rows($res_loc);
					if($num_loc > 0){
                   		$j=1;
                   		while($row_loc = mysqli_fetch_array($res_loc)){
                	?>
                  	<tr>
                    	<td style="border:none" class="bg-success"><?=$row_loc['displayname']?></td>
                    </tr>
                    <?php 
				   	$k=1;
				   	$sql_locdet = "select locationname, location_code from location_master where locationtype='".$row_loc['usedname']."' and statusid='1' order by locationname";
                   	$res_locdet = mysqli_query($link1,$sql_locdet) or die(mysqli_error($link1));
					if(mysqli_num_rows($res_locdet)>0){
                   	while($row_locdet = mysqli_fetch_array($res_locdet)){
						if($k%4==1){
						?>
                    <tr>
					<?php }
					$res_accloc = mysqli_query($link1, "select id from access_location where status='1' and location_code='".$row_locdet['location_code']."' and userid='".$_REQUEST['userid']."'");
					$num_accloc = mysqli_num_rows($res_accloc);
					?>
                      <td><input style="width:20px" type="checkbox" id="report2" name="report2[]" value="<?=$row_locdet['location_code']?>" <?php if($num_accloc > 0) echo "checked";?> />
                        <?=$row_locdet['locationname']?></td>
                    <?php if($k/4==0){?>
            		</tr>
            		<?php 
							} 
					$k++;
					} }else{?>
                    <tr><td>&nbsp;</td></tr>
                    <?php }$j++;}}?>
                  </tbody>
                </table>
              </div>
              <div class="form-buttons" align="center">
                <button title="Previous" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='#menu1'"><i class="fa fa-backward fa-lg"></i>&nbsp;&nbsp;Previous</button>
                <button class='btn<?=$btncolor?>' id="submitTab2" type="submit" name="submitTab2" value="Save"><i class="fa fa-save fa-lg"></i>&nbsp;&nbsp;Save</button>
                <button title="Next" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='#menu3'">Next&nbsp;&nbsp;<i class="fa fa-forward fa-lg"></i></button>
                <button title="Back" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='addAdminUser.php?op=edit&id=<?php echo $_REQUEST['userid'];?>&srch=<?php if(isset($_REQUEST['srch'])){ echo $_REQUEST['srch'];}?>&status=<?php if(isset($_REQUEST['status'])){ echo $_REQUEST['status'];}?><?=$pagenav?>'"><i class="fa fa-reply fa-lg"></i>&nbsp;&nbsp;Back</button>
                </div>
            </form>
           </div>
            
            <!-- Tab 4 Process Skill Rights-->
          <div id="menu3" class="tab-pane fade">
            <form id="frm3" name="frm3" class="form-horizontal" action="" method="post">
              <div class="table-responsive">
                <div class="form-buttons" style="float:right">
                  <input name="CheckAll" type="button" class="btn<?=$btncolor?>" onClick="checkAll(document.frm3.skill)" value="Check All" />
                  <input name="UnCheckAll" type="button" class="btn<?=$btncolor?>" onClick="uncheckAll(document.frm3.skill)" value="Uncheck All" />
                </div>
                <table id="myTable4" class="table table-hover">
                    <thead>
                        <tr class="<?= $tableheadcolor ?>">
                            <th width="10%">Select</th>
                            <th width="90%">Process Skill</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						$assign_skill = explode(",",$row_user['process_ids']);
                        $j=1;
                        $res_workcat = mysqli_query($link1,"SELECT * FROM process_step_master WHERE status='1' ORDER BY process_id");
                        while($row_workcat=mysqli_fetch_assoc($res_workcat)){
                            //// check access skill
                            if(in_array($row_workcat['process_id'], $assign_skill)){ $numacc_skill=1;}else{$numacc_skill=0;}
                        ?>
                        <tr>
                            <td align="center"><input style="width:20px" type="checkbox" id="skill" name="skill[]" value="<?=$row_workcat['process_id']?>" <?php if($numacc_skill > 0) echo "checked";?>/></td>
                            <td align="left"><?=$row_workcat['process_name']?></td>
                        </tr>
                        <?php  $j++;}?>
                    </tbody>
                </table>
              </div>
              <div class="form-buttons" align="center">
                <button title="Previous" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='#menu2'"><i class="fa fa-backward fa-lg"></i>&nbsp;&nbsp;Previous</button>
                <button class='btn<?=$btncolor?>' id="submitTab3" type="submit" name="submitTab3" value="Save"><i class="fa fa-save fa-lg"></i>&nbsp;&nbsp;Save</button>
                <button title="Next" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='#menu4'">Next</button>
                <button title="Back" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='addAdminUser.php?op=edit&id=<?php echo $_REQUEST['userid'];?>&srch=<?php if(isset($_REQUEST['srch'])){ echo $_REQUEST['srch'];}?>&status=<?php if(isset($_REQUEST['status'])){ echo $_REQUEST['status'];}?><?=$pagenav?>'"><i class="fa fa-reply fa-lg"></i>&nbsp;&nbsp;Back</button>
                </div>
            </form>
           </div>
           <!-- Tab 5 product category Rights-->
          <div id="menu4" class="tab-pane fade">
            <form id="frm4" name="frm4" class="form-horizontal" action="" method="post">
              <div class="table-responsive">
                <table id="myTable5" class="table table-hover">
                  <tbody>
                  	<?php 
					  $arr_productcat=array();
					  $arr_product=array();
					  $res_prdcat=mysqli_query($link1,"select distinct(product_category) as prdcat, product_category from product_sub_category where status='1' order by product_category")or die(mysqli_error($link1));
					  while($row_prdcat=mysqli_fetch_array($res_prdcat)){
							$arr_productcat[]=$row_prdcat[0];
							$arr_product[]=$row_prdcat[1];
					  }
					  ?>
                  	<?php
                 	for($j=0;$j<count($arr_productcat);$j++){?>
                  	<tr>
                    	<td style="border:none" class="<?=$tableheadcolor?>"><?=$arr_product[$j]?>&nbsp;<input style="width:20px"  type="checkbox" id="funcTB4<?=$j?>" name="funcTB4<?=$j?>[]" onClick="checkFunc(document.frm4.report14<?=$j?>,'<?=$j?>','funcTB4');"/></td>
                    </tr>
                    <?php
                   $i=1;
                   $report="select * from product_sub_category where product_category='$arr_productcat[$j]' and status='1' ORDER by prod_sub_cat";
                   $rs_report=mysqli_query($link1,$report) or die(mysqli_error($link1));
                   while($row_report=mysqli_fetch_array($rs_report)){
                     if($i%5==1){?>
                    <tr>
					<?php 
                     }
                   $state_acc=mysqli_query($link1,"select id from mapped_productcat where status='Y' and prod_subcatid='$row_report[psubcatid]' and product_cat='$arr_productcat[$j]' and userid='$_REQUEST[userid]'")or die(mysqli_error($link1));
                   $num1=mysqli_num_rows($state_acc);?>
                      <td><input style="width:20px"  type="checkbox" id="report14<?=$j?>" name="report14<?=$j?>[]" value="<?=$row_report[psubcatid]?>" <?php if($num1 > 0) echo "checked";?> />&nbsp;<?=$row_report['prod_sub_cat']?></td>
                    <?php if($i/5==0){?>
            		</tr>
                    <?php 
					  }
			        $i++;
				   }////// close while loop
				 }////// close for loop
                 ?>
                    <input name="count_repTab4" id="count_repTab4" type="hidden" value="<?=$j?>"/>
                  </tbody>
                </table>
              </div>
              <div class="form-buttons" align="center">
                <button title="Previous" type="button" class="btn<?=$btncolor?>" onClick="window.location.href='#menu3'">Previous</button>
             	<input type="submit" class="btn<?=$btncolor?>" name="submitTab4" id="submitTab4" value="Save"/>
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

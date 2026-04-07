<?php
////// Function ID ///////
$fun_id = array("a"=>array(17));
//////////////////////////
require_once("../includes/config.php");
////// Access check //////
if(!access_check_v3($link1, $fun_id, $_SESSION["userid"], $_SESSION["utype"])){exit;}
//////////////////////////
$_SESSION['raisePoAddSession']="";
if(isset($_REQUEST['status'])){$selstatus=$_REQUEST['status'];}else{$selstatus="";}
if(isset($_REQUEST['request_from'])){$selrf=$_REQUEST['request_from'];}else{$selrf="";}
if(isset($_REQUEST['request_to'])){$selrt=$_REQUEST['request_to'];}else{$selrt="";}
if(isset($_REQUEST['daterange'])){$seldaterng=$_REQUEST['daterange'];}else{$seldaterng="";}
$get_opr_rgts = getOprRights($_SESSION['userid'],$_REQUEST['pid'],$link1);
/////////check approval is applicable or not
$apply_app = getAnyDetails(base64_decode($_REQUEST['pid']),"apply_approval","tabid","tab_master",$link1);
///// get access location details
$access_loc = getAccessLocation($_SESSION['userid'],$link1);
/////get status//
$arrstatus = getFullStatus("",$link1);
?>
<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="shortcut icon" href="../images/titleimg.png" type="image/png">
 <script src="../js/jquery.js"></script>
 <?php 
 include("../includes/fontawesome.html");
 include("../includes/main_css.html"); 
 include("../includes/bootstrap.html");
 include("../includes/datatables.html");
 ?>
 <script type="text/javascript" language="javascript" >
$(document).ready(function() {
	var dataTable = $('#po_status_list-grid').DataTable( {
		"processing": true,
		"serverSide": true,
		"order": [[ 0, "desc" ]],
		"ajax":{
			url :"../pagination/po_status_list-grid-data.php", // json datasource
			data: { "pid": "<?=$_REQUEST['pid']?>", "hid": "<?=$_REQUEST['hid']?>", "icn": "<?=$_REQUEST['icn']?>", "status": "<?=$selstatus?>", "request_from": "<?=$selrf?>", "request_to": "<?=$selrt?>", "daterange": "<?=$seldaterng?>"},
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".po_status_list-grid-error").html("");
				$("#po_status_list-grid").append('<tbody class="po_status_list-grid-error"><tr><th colspan="<?php if($apply_app=="Y"){echo "13";}else{ echo "12";}?>">No data found in the server</th></tr></tbody>');
				$("#po_status_list-grid_processing").css("display","none");
				
			}
		}
	} );
} );
$(document).ready(function(){
	$('input[name="daterange"]').daterangepicker({
		locale: {
			format: 'YYYY-MM-DD'
		},
		startDate: "<?=date("Y-m-01")?>"
	});
});
</script>
 <!-- Include Date Range Picker -->
  <script type="text/javascript" src="../js/moment.js"></script>
 <script type="text/javascript" src="../js/daterangepicker.js"></script>
 <link rel="stylesheet" type="text/css" href="../css/daterangepicker.css"/>
<title><?=siteTitle?></title>
</head>
<body>
<div class="container-fluid">
  <div class="row content">
	<?php 
    include("../includes/leftnav_admin.php");
    ?>
    <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
      <h2 align="center"><i class="fa <?=$fa_icon?>"></i> Raise PO List</h2>
      <?php if(isset($_REQUEST['msg'])){?>
        <div class="alert alert-<?php echo $_REQUEST['chkflag'];?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            <strong><?php echo $_REQUEST['chkmsg'];?>!</strong>&nbsp;&nbsp;<?=$_REQUEST['msg']?>.
        </div>
      <?php }?>
      <?php
		if(isset($_SESSION["logres"]) && $_SESSION["logres"]){
		echo '<div class="py-2 overflow-hidden" style="background:#f1f1f1;padding:15px;line-height:20px;color:#e51111;margin:15px;font-size:12px;">';
		echo '<i class="fa fa-exclamation-circle" aria-hidden="true"></i> '.$_SESSION["logres"]["msg"];
		echo '<br/><i class="fa fa-exclamation-circle" aria-hidden="true"></i> '.implode(" , ",$_SESSION["logres"]["invalid"]);
		echo '</div>';
		}
		unset($_SESSION["logres"]);
		?>
	  <form class="form-horizontal" role="form" name="form1" action="" method="get">
	   <div class="form-group">
         <div class="col-md-6"><label class="col-md-5 control-label">Raise From</label>	  
			<div class="col-md-6" align="left">
			   <select name="request_from" id="request_from" class="form-control custom-select">
			   		<option value=''>All</option>
					<?php
					$res_maploc = mysqli_query($link1,"select location_code,locationname from location_master where statusid='1' and location_code in (".$access_loc.") order by locationname "); 
					while($row_maploc = mysqli_fetch_assoc($res_maploc)){
						?>
					<option value="<?=$row_maploc['location_code']?>" <?php if($selrf == $row_maploc['location_code']) { echo 'selected'; }?>><?=$row_maploc['locationname']." (".$row_maploc['location_code'].")"?></option>
					<?php } ?>
               </select>
            </div>
          </div>
		  <div class="col-md-6"><label class="col-md-5 control-label">Raise To</label>
			<div class="col-md-6" align="left">
			   <select name="request_to" id="request_to" class="form-control custom-select">
			   		<option value=''>All</option>
					<?php
					$res_maploc = mysqli_query($link1,"select location_code,locationname from location_master where statusid='1' and location_code in (".$access_loc.") order by locationname "); 
					while($row_maploc = mysqli_fetch_assoc($res_maploc)){
						?>
					<option value="<?=$row_maploc['location_code']?>" <?php if($selrt == $row_maploc['location_code']) { echo 'selected'; }?>><?=$row_maploc['locationname']." (".$row_maploc['location_code'].")"?></option>
					<?php } ?>
               </select>
            </div>
          </div>
	    </div><!--close form group-->
	    <div class="form-group">
         <div class="col-md-6"><label class="col-md-5 control-label"> Status</label>	  
			<div class="col-md-6" align="left">
			   <select name="status" id="status" class="form-control custom-select">
               		<option value=""<?php if($selstatus==''){ echo "selected";}?>>All</option>
               		<?php foreach($arrstatus as $key => $value){
						if($key!=1 && $key!=2){
						?>
                    	<option value="<?=$key?>" <?php if($selstatus == $key) { echo 'selected'; }?>><?=$value?></option>
                    <?php }} ?>
                    
                </select>
            </div>
          </div>
		  <div class="col-md-6"><label class="col-md-5 control-label"></label>
			<div class="col-md-5" align="left">
			   <input name="pid" id="pid" type="hidden" value="<?=$_REQUEST['pid']?>"/>
               <input name="hid" id="hid" type="hidden" value="<?=$_REQUEST['hid']?>"/>
               <input name="icn" id="icn" type="hidden" value="<?=$_REQUEST['icn']?>"/>
               <button class='btn<?=$btncolor?>' id="Submit" type="submit" name="Submit" value="GO" title="Go!"><i class="fa fa-arrow-circle-right fa-lg"></i>&nbsp;GO</button>
            </div>
          </div>
	    </div><!--close form group-->
        <div class="form-group">
		  <div class="col-md-6"><label class="col-md-5 control-label">Date Range</label>	  
			<div class="col-md-6" align="left">
				<input type="text" name="daterange" id="date_rng" class="form-control" value="<?=$seldaterng?>" />
            </div>
          </div>
          <div class="col-md-6"><label class="col-md-5 control-label"></label>
            <div class="col-md-6">
               <?php
			    ////// check this user have right to export the excel report
			    	if($get_opr_rgts['excel']=="Y"){
			   ?>
				
				<a href="../excelReports/po_status_list_csv.php?rname=<?=base64_encode("po_status_list_csv.php")?>&rheader=<?=base64_encode("PO Detail Excel")?>&daterange=<?=$seldaterng;?>&req_from=<?=base64_encode($selrf)?>&req_to=<?=base64_encode($selrt);?>&status=<?=base64_encode($selstatus);?>" title="Export PO Status details in excel"><i class="fa fa-file-excel-o fa-2x faicon excelicon" title="Export Pending PO Status details in excel"></i>Pending PO Details</a>
				<br>
              <a href="../excelReports/po_status_list_excel.php?daterange=<?=$seldaterng;?>&req_from=<?=base64_encode($selrf);?>&req_to=<?=base64_encode($selrt);?>&status=<?=base64_encode($selstatus);?>" title="Export PO Status details in excel"><i class="fa fa-file-excel-o fa-2x faicon excelicon" title="Export PO Status details in excel"></i></a>
               <?php
			}
				?>
            </div>
          </div>
	    </div><!--close form group-->
	  </form>
      <form class="form-horizontal" role="form">
      <?php
      if($get_opr_rgts['add']=="Y"){
      ?>
      	<button title="Upload PO" type="button" class="btn<?=$btncolor?>" style="float:right;" onClick="window.location.href='raise_po_upload.php?op=add<?=$pagenav?>'"><i class="fa fa-upload fa-lg"></i>&nbsp;&nbsp;Upload PO</button>&nbsp;&nbsp;
        <button title="Add Raise PO" type="button" class="btn<?=$btncolor?>" style="float:right;" onClick="window.location.href='raise_po_add.php?op=add<?=$pagenav?>'"><i class="fa fa-plus-circle fa-lg"></i>&nbsp;&nbsp;Add Raise PO</button>&nbsp;&nbsp;
        <?php } ?> 
        <div class="form-group"  id="page-wrap" style="margin-left:10px;"><br/>
      <!--<div class="form-group table-responsive"  id="page-wrap" style="margin-left:10px;"><br/><br/>-->
       <table  width="100%" id="po_status_list-grid" class="display" align="center" cellpadding="4" cellspacing="0" border="1">
          <thead>
            <tr class="<?=$tableheadcolor?>">
              <th>S.No</th>
              <th>Raise From</th>
              <th>Raise To</th>
				
              <th>System Ref. No.</th>
				<th>Type</th>
              <th>Date & Time</th>
              <th>Entry By</th>
              <th>Status</th>
              <th>Doc. Type</th>
              <th>View</th>
              <?php if($apply_app=="Y"){?>
              <th>Approval</th>
              <?php }?>
              <th>Revise</th>
			  <th>Cancel</th>
			  <th>Print</th>
            </tr>
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

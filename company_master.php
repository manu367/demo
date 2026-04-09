<?php
require_once("../includes/config.php");
$_SESSION["messageIdent"]="";
////// initialize filter values
if(isset($_REQUEST['status'])){$selstatus=$_REQUEST['status'];}else{$selstatus="";}
//////////// get operational rights
//$get_opr_rgts = getOprRights($_SESSION['userid'],$_REQUEST['pid'],$link1);
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
<link rel="stylesheet" type="text/css" href="../css/dataTables.responsive.css">
<script type="text/javascript" language="javascript" src="../js/dataTables.responsive.min.js"></script>
 <script type="text/javascript" language="javascript" >
$(document).ready(function() {
	var dataTable = $('#company-grid').DataTable( {
		"responsive": true, 
		"processing": true,
		"serverSide": true,
		"order":  [[0,"asc"]],
		"ajax":{
			url :"../pagination/company-grid-data.php", // json datasource
			data: { "pid": "<?=$_REQUEST['pid']?>", "hid": "<?=$_REQUEST['hid']?>", "icn": "<?=$_REQUEST['icn']?>", "status": "<?=$selstatus?>"},
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".company-grid-error").html("");
				$("#company-grid").append('<tbody class="company-grid-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
				$("#company-grid_processing").css("display","none");
				
			}
		}
	} );
} );
</script>
<title><?=siteTitle?></title>
</head>
<body>
<div class="container-fluid">
  <div class="row content">
	<?php 
    include("../includes/leftnav_admin.php");
    ?>
    <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
      <h2 align="center"><i class="fa <?=$fa_icon?>"></i> Company Master</h2>

      <?php if(isset($_REQUEST['msg'])){?>
        <div class="alert alert-<?php echo $_REQUEST['chkflag'];?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            <strong><?php echo $_REQUEST['chkmsg'];?>!</strong>&nbsp;&nbsp;<?=$_REQUEST['msg']?>.
        </div>
      <?php }?>

	  <form class="form-horizontal" role="form" name="form1" action="" method="get">
	   
	    <div class="form-group">
         <div class="col-md-6"><label class="col-md-5 control-label">&nbsp;</label>
			<div class="col-md-5" align="left">
			   
            </div>
          </div>
		  <div class="col-md-6"><label class="col-md-5 control-label">Status</label>
			<div class="col-md-5" align="left">
            	<select name="status" id="status" class="form-control">
                    <option value=""<?php if(isset($_REQUEST['status'])){if($_REQUEST['status']==''){ echo "selected";}}?>>All</option>
                    <option value="A"<?php if(isset($_REQUEST['status'])){if($_REQUEST['status']=="A"){ echo "selected";}}?>>Active</option>
                    <option value="D"<?php if(isset($_REQUEST['status'])){if($_REQUEST['status']=="D"){ echo "selected";}}?>>Deactive</option>
                </select>
            </div>
            <div class="col-md-1" align="left">    
			  	<input name="pid" id="pid" type="hidden" value="<?=$_REQUEST['pid']?>"/>
               	<input name="hid" id="hid" type="hidden" value="<?=$_REQUEST['hid']?>"/>
                <input name="icn" id="icn" type="hidden" value="<?=$_REQUEST['icn']?>"/>
               	<input name="Submit" type="submit" class="btn<?=$btncolor?>" value="GO"  title="Go!">
            </div>
          </div>
	    </div><!--close form group-->

        <div class="form-group">
          <div class="col-md-6"><label class="col-md-5 control-label"></label>
            <div class="col-md-5">
              <?php
			    ////// check this user have right to export the excel report
			    //if($get_opr_rgts['excel']=="Y"){
			   ?>
               <a href="excelexport.php?rname=<?=base64_encode("companymaster")?>&rheader=<?=base64_encode("Company Master")?>&status=<?=base64_encode($_REQUEST['status'])?>" title="Export details in excel"><i class="fa fa-file-excel-o fa-2x" title="Export details in excel"></i></a> 
               <?php
				//}
				?>
            </div>
          </div>

		  <div class="col-md-6"><label class="col-md-5 control-label"></label>	  
			<div class="col-md-5" align="left">
               
            </div>
          </div>
	    </div><!--close form group-->
	  </form>
      <form class="form-horizontal" role="form">
        <?php
        ////// check this user have right to add new entry
        //if($get_opr_rgts['add']=="Y"){
        ?>
          <button title="Add New Company" type="button" class="btn<?=$btncolor?>" style="float:right;" onClick="window.location.href='addcompany.php?op=add<?=$pagenav?>'"><i class="fa fa-plus-circle fa-lg"></i>&nbsp;&nbsp;Add Company</button>&nbsp;&nbsp;
        <?php //} ?>      
        <div class="form-group table-responsive" id="page-wrap" style="margin-left:10px;"><br/>
      <!--<div class="form-group table-responsive"  id="page-wrap" style="margin-left:10px;"><br/><br/>-->
       <table  width="98%" id="company-grid" class="display table-striped" align="center" cellpadding="4" cellspacing="0" border="1">
          <thead>
            <tr class="<?=$tableheadcolor?>">
              <th>S.No</th>
              <th>Company Code</th>
              <th>Company Name</th>
              <th>Contact Person</th>
              <th>Contact No.</th>
              <th>City</th>
              <th>State</th>
              <th>Invoice Series</th>
              <th>STN Series</th>
              <th>Accessories Invoice Series</th>
              <th>Damage Bill Series</th>
              <th>Status</th>
              <th>View/Edit</th>
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

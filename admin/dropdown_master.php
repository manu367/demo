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
    <link rel="stylesheet" href="../css/dataTables.responsive.css">
    <script type="text/javascript" src="../js/dataTables.responsive.min.js"></script>

    <?=ajaxCall('drop_down_grid','../pagination/dropdown-grid-data.php',["pid"=>$_REQUEST['pid'],"hid"=>$_REQUEST['hid']])?>
</head>
<body>
<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <h2 align="center" style="font-style: italic"><i class="fa fa-users"></i> Dropdown Master</h2>


            <!--            pid and hid hidden form-->
            <form class="form-horizontal" role="form" name="form1" action="" method="get">
                <input name="pid" id="pid" type="hidden" value="<?=$_REQUEST['pid']?>"/>
                <input name="hid" id="hid" type="hidden" value="<?=$_REQUEST['hid']?>"/>
            </form>

           <?php
           if(PermissionManager::checkaddRights($link1,$_SESSION['userid'],$_REQUEST['pid'])){
           ?>
               <div class="text-right">
                   <a href="add_dropdown.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>"
                      class="btn btn-primary">Add Dropdown</a>
               </div>
            <?php } ?>

            <form class="form-horizontal" role="form">
                &nbsp;&nbsp;
                <div class="form-group"  id="page-wrap" style="margin-left:10px;"><br/><br/>

                    <table  width="95%" id="drop_down_grid" class="display table-striped" align="center" cellpadding="4" cellspacing="0" border="1">
                        <thead>
                        <tr class="<?=$tableheadcolor?>">
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Table</th>
                            <th>Status</th>
                            <th>Action</th>
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
<script>
    document.querySelectorAll("table td, table th").forEach((cell) => {
        cell.style.textTransform = "capitalize";
    });
</script>
</html>
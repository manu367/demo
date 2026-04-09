<?php
require_once("../includes/config.php");
if(isparamValidation([$_REQUEST['formid'],$_REQUEST['token']],false)){
    var_dump("done");
}else{
    var_dump("values are missing");
}

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
</head>
<body>
<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <h2 align="center" style="font-style: italic"><i class="fa fa-users"></i> Publish Form</h2>
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
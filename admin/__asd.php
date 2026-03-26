<?php
require_once("../includes/config.php");

function loadFMsColumnTableName($link,$fmsid){
    $column=null;
    $result=mysqli_query ($link,"SELECT * FROM fms_master WHERE id=$fmsid");
    if($result){
        while ($row=mysqli_fetch_assoc($result)){
            $column=$row;
        }
    }
    return $column;
}

$value=loadFMsColumnTableName($link1,251);
var_dump($value);exit();
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
</head>
<body>
<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <h2 align="center"><i class="fa fa-users"></i> Call Master</h2>
            <span><?=$_SESSION['csrf_manu']?></span>


        </div>
    </div>
</div>
<script>
    // singleton obj have only one object of over the class

</script>
<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>

</body>
</html>
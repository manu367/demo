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
    <script>
        $(document).ready(function () {
            $('#myacc-users-grid').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "../pagination/fms-grid-data-view.php",
                    type: "POST",
                    data: {
                        pid: "<?= $_REQUEST['pid'] ?? '' ?>",
                        hid: "<?= $_REQUEST['hid'] ?? '' ?>"
                    },
                    error: function () {
                        $("#myacc-users-grid").append(
                            '<tbody><tr><td colspan="10">No data found</td></tr></tbody>'
                        );
                        // $("#myacc-users-grid_processing").hide();
                    }
                }
            });
        });
    </script>


</head>
<body>
<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav21.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <h2 align="center" style="font-style: italic"><i class="fa fa-users"></i> FMS View</h2>
            <span><?=$_SESSION['csrf_manu']?></span>
            <?php
            for($i=0;$i<100;$i++){
                echo "<span>".base64_encode($i)."</span><br/>";
            }
            ?>
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
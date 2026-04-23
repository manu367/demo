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
    <script type="text/javascript" src="../js/moment.js"></script>
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title><?=siteTitle?></title>
    <style>
        .error-box {
            text-align: center;
            max-width: 100%;
            margin: 100px;
        }

        .error-code {
            font-size: 120px;
            font-weight: bold;
            letter-spacing: 5px;
        }

        .error-text {
            font-size: 22px;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .btn-home {
            padding: 10px 25px;
            font-size: 16px;
            border-radius: 25px;
            text-decoration: none;
            background: #ddd;
            color: #2c5364;
            transition: 0.3s;
        }

        .btn-home:hover {
            background: #ddd;
            text-decoration: none;
        }

        .subtle {
            margin-top: 15px;
            font-size: 14px;
            opacity: 0.6;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <div class="error-box">
                <div class="error-code">404</div>
                <div class="error-text" style="text-transform: capitalize"> this page doesn’t exist.</div>

                <a href="/" class="btn-home">Go Home</a>

                <div class="subtle">
                    <?php
                    if(isset($_SESSION['userid'])){
                        echo "User ID: " . $_SESSION['userid'];
                    }
                    ?>
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
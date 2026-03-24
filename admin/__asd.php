<?php
require_once("../includes/config.php");

class Singleton
{
    public static function getInstance(){
        static $instance;
        if($instance == null){
            $instance = new self();
        }
        return $instance;
    }

    public function __toString(){
        return "this is sinlgeton instant";
    }
}
class FactoryPattern{
    public static function getObject($role){
        if($role ==='admin'){
            return Singleton::getInstance();
        }
        if($role==="mysqli"){
            return mysqli_connect("localhost","root","","okaya_beta","3306");
        }
    }
    public function __toString()
    {
        return "";
    }
}

$a=Singleton::getInstance();
$b=Singleton::getInstance();
$connection=FactoryPattern::getObject("mysqli");
echo $a;

var_dump("sddc");exit();

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

            <!--            pid and hid hidden form-->
            <form class="form-horizontal" role="form" name="form1" action="" method="get">
                <div class="form-group">
                    <div class="col-md-6"><label class="col-md-5 control-label"></label>
                        <div class="col-md-5">
                            <input name="pid" id="pid" type="hidden" value="<?=$_REQUEST['pid']?>"/>
                            <input name="hid" id="hid" type="hidden" value="<?=$_REQUEST['hid']?>"/>
                        </div>
                    </div>
                    <div class="col-md-6"><label class="col-md-5 control-label"></label>
                        <div class="col-md-5" align="left">
                        </div>
                    </div>
                </div><!--close form group-->
            </form>
            <div class="col-md-12" style="display: flex;justify-content: flex-end">
                <div style="display: flex;align-items: center">
                    <div style="display: flex; justify-content: center;align-items: center">
                        <label>Text Here</label>
                        <input id type="text" name="" id="input" class="form-control">
                    </div>
                    <button id="btn_go" class="btn btn-success" style="margin-left: 10px">Go</button>
                </div>
            </div>
            <form>
                <table width="100%" id="itemsTable1" class="table table-bordered table-hover" >
                    <thead class="bg-primary text-center">
                    <tr>
                        <td>Sr.No</td>
                        <td>value</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1.</td>
                        <td>this is text-value</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary">🖊</button>
                            <button type="button" class="btn btn-danger">🎁</button>
                            <button type="button" class="btn" style="background-color: black;color: whitesmoke">👍</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
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
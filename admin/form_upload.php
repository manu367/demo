<?php
require_once("../includes/config.php");
global $link1;
if(isset($_REQUEST['formid'])){
    $formid = $_REQUEST['formid'];
    $fms_form=FMsBasicOperation::loadform($link1,$formid);
    $fms_data=FMsBasicOperation::loadFMS($link1,$fms_form['fms_id']);
//    var_dump($fms_form,$fms_data);
}




?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=siteTitle?></title>
    <script src="../js/jquery.min.js"></script>
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/abc.css" rel="stylesheet">
    <script src="../js/bootstrap.min.js"></script>
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-select.min.css">
    <script src="../js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/frmvalidate.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/common_js.js"></script>
    <link rel="stylesheet" href="../css/datepicker.css">
    <script src="../js/bootstrap-datepicker.js"></script>
    <script src="../js/fileupload.js"></script>
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
            z-index: 1000;
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
                }, 60000); // hide after 3s
            }
        });
    </script>
</head>
<body>

<?php
if(isset($_REQUEST['msg'])){?>
    <div id="errorPopup" class="toast" style="<?=isset($_REQUEST['type'])?'background: green;':'background: #cd1a1a;'?>">
        <span class="message"><?=htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8');?></span>
    </div>
<?php } ?>


<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <h2 align="center"><i class="fa fa-upload"></i>Upload FMS Form</h2>
<!--            template-->
            <div style="display:inline-block;float:right">
                <a href="../templates/fms_form_template.php?tabname=<?=$fms_data['table_name']?>" title="Download Excel Template">
                    <img src="../images/template.png" title="Download Excel Template"/>
                </a>
            </div>
            <div class="form-group"  id="page-wrap" style="margin-left:10px;">
                <form  name="frm1"  id="frm1" class="form-horizontal" action="form_upload_data.php" method="post"  enctype="multipart/form-data">
                    <input name="fms_id" value="<?=$fms_data['id']?>" type="hidden">
                    <input name="form_id" value="<?=$fms_form['id']?>" type="hidden">
                    <input name="table_name" value="<?=$fms_data['table_name']?>" type="hidden">

                    <div class="form-group">
                        <div class="col-md-12"><label class="col-md-4 control-label">FMS Name</label>
                            <div class="col-md-4">
                                <?php
                                $fms_value="";
                                $isDiabled=false;
                                if($fms_data['fmsname']){
                                    $fms_value=$fms_data['fmsname'];
                                    $isDiabled=true;
                                }
                                ?>
                                <input name="fms_name" class="form-control" value="<?=$fms_value?>" <?= $isDiabled?'disabled="disabled"':''?>>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12"><label class="col-md-4 control-label">Form Name</label>
                            <div class="col-md-4">
                                <?php
                                $valu="";
                                $isDisabled=false;
                                if($fms_form['form_name']){
                                    $value=$fms_form['form_name'];
                                    $isDisabled=true;
                                }
                                ?>
                                <input name="form_name" class="form-control" value="<?=$value?>" <?= $isDisabled?'disabled="disabled"':''?>>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12"><label class="col-md-4 control-label">Upload File</label>
                            <div class="col-md-4">
                                <input type="file" name="form_upload_file" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top: 20px">
                        <div class="col-md-12">
                            <div class="col text-center">
                                <input type="submit" name="upload_file" class="btn btn-success" value="upload">
                            </div>
                        </div>
                    </div>
                </form>
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
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
    <title><?=siteTitle?></title>
    <!--    alert box css-->
    <style>
        .table-wrapper {
            border-top-right-radius: 12px;
            border-top-left-radius: 12px;
            overflow: hidden; /* IMPORTANT */
            border: 1px solid #ddd;
        }
        #customAlertContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .alert-box {
            min-width: 250px;
            max-width: 90vw;
            padding: 12px 16px;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideIn 0.3s ease;
        }

        .alert-success { background: #28a745; }
        .alert-error { background: #dc3545; }
        .alert-warning { background: #ffc107; color: #000; }

        .close-btn {
            margin-left: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* 📱 Mobile tweak */
        @media (max-width: 600px) {
            #customAlertContainer {
                top: auto;
                bottom: 20px;
                right: 10px;
                left: 10px;
            }

            .alert-box {
                width: 100%;
            }
        }
    </style>
    <style>
        .toast {
            position: fixed;
            top: 20px;
            right: -350px;
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(8px);
            z-index: 9999;
            color: #fff;
            padding: 14px 18px;
            border-radius: 10px;

            box-shadow: 0 8px 25px rgba(0,0,0,0.2);

            font-size: 14px;
            font-weight: bold;
            min-width: 250px;

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
    <style>
        /* Snackbar */
        #snackbar {
            visibility: hidden;
            min-width: 250px;
            max-width: 80%;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 16px;

            /* Magic for center bottom */
            position: fixed;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s, bottom 0.3s;
        }

        #snackbar.show {
            visibility: visible;
            opacity: 1;
            bottom: 50px;
        }
    </style>
</head>
<body>
<div style="display:none">
    <?=$selectedBox?>
</div>
<?php
if(isset($_REQUEST['msg'])):?>
    <div id="errorPopup" class="toast" style="background-color: <?=isset($_REQUEST['type'])?'darkred':'green'?>">
        <span class="icon">⚠️</span>
        <span class="message"><?=$_REQUEST['msg']?></span>
    </div>
    <script>
        $(document).ready(function(){
            let toast = $("#errorPopup");

            if(toast.length){
                setTimeout(() => {
                    toast.addClass("show");
                }, 500);

                setTimeout(() => {
                    toast.removeClass("show");
                }, 60000);
            }
        });
    </script>
<?php endif; ?>



<div class="container-fluid">
    <div class="row content">
        <?php include("../includes/leftnav2.php"); ?>
        <div class="<?=$screenwidth?>">
            <h2 align="center" style="text-transform: capitalize">
                <i class="fa fa-users"></i> <?=$operation?> Form</h2><br/><br/>
            <?php if($isPermission){ ?>
                <form  name="frm1" id="frm1" class="form-horizontal" action="" method="post">
                    <input type="text" id="fmsid" name="fmsid" value="<?=$load['id']?>" style="display: none;">
                    <input type="text" id="formid" name="formid" value="<?=$res['id']?>" style="display: none;">
                    <?php
                    if (!empty($res['parameter_name'])) {
                        echo '<input type="hidden" id="old_column" name="old_column" value="'
                            . htmlspecialchars(json_encode($res['parameter_name']), ENT_QUOTES, 'UTF-8')
                            . '">';
                    }
                    ?>
                    <div class="form-group"  id="page-wrap" style="margin-left:10px;" >

                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="user_id" class="col-md-6 control-label">FMS</label>
                                <div class="col-md-6">
                                    <input name="fmsname" id="fmsname" type="text" class="form-control" value="<?php echo $load['fmsname'] ?? ''; ?>" disabled required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="col-md-6 control-label">Details</label>
                                <div class="col-md-6">
                                    <input name="fms_details" id="fms_details" type="text"
                                           class="form-control" value="<?php echo $load['details'] ?? ''; ?>" disabled required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6"><label class="col-md-6 control-label">Form name</label>
                                <div class="col-md-6">
                                    <input name="frm_name" id="frm_name" type="text" class="form-control" placeholder="Form Name" value="<?php echo $res['form_name'] ?? ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6"><label class="col-md-6 control-label">Sequance</label>
                                <div class="col-md-6">
                                    <input name="frm_seq" id="frm_seq" type="number" class="form-control" placeholder="form Sequance" value="<?php echo $res['frm_seq'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--                second page-->
                    <div class="form-group"  id="page-wrap" style="margin-left:10px;"><br/><br/>
                        <h4 class=""><b>Paramerters</b></h4>
                        <div class="table-wrapper">
                            <table  width="100%" id="form_table" class="display" align="center" cellpadding="4" cellspacing="0" border="1">
                                <thead>
                                <tr class="<?=$tableheadcolor?>">
                                    <th style="padding: 8px;">#</th>
                                    <th style="text-align: center; padding: 8px;">Name</th>
                                    <th style="text-align: center; padding: 8px;">Display Name</th>
                                    <th style="text-align: center; padding: 8px;">Type</th>
                                    <th style="text-align: center; padding: 8px;">length</th>
                                    <th style="text-align: center; padding: 8px;">Required</th>
                                    <th style="text-align: center; padding: 8px;">Action</th>
                                </tr>
                                </thead>
                                <tbody id="addform">
                                <?php
                                $co     = json_decode($res['parameter_name'], true) ?? [];
                                $dis    = json_decode($res['display_name'], true) ?? [];
                                $type   = json_decode($res['type'], true) ?? [];
                                $length = json_decode($res['length'], true) ?? [];
                                $param_require=json_decode($res['param_require'],true) ?? [];
                                $dropdown=json_decode($res['drop_down'],true)??[];
                                $countleave=0;

                                $result = mysqli_query($link1, "SELECT * FROM parameter_type WHERE status = '1'");
                                $optionsData = [];

                                if ($result) {
                                    while ($r = mysqli_fetch_assoc($result)) {
                                        $optionsData[] = $r;
                                    }
                                }

                                if (!empty($co)) {
                                    for ($i = 0; $i < count($co); $i++) {
                                        $countleave = $i + 1;

                                        $isChecked = (isset($param_require[$i]) && $param_require[$i] == 1);
                                        $checkedAttr = $isChecked ? "checked" : "";
                                        $hiddenValue = $isChecked ? 1 : 0;
//                                        var_dump($param_require[$i], $checkedAttr);
                                        echo "<tr>
            <td>".($i+1)."</td>
            <td><input type='text' class='form-control' name='param_name[]' data-old='".($co[$i] ?? "")."' value='".($co[$i] ?? "")."'></td>
            <td><input type='text' class='form-control' name='display_name[]' data-old='".($dis[$i] ?? "")."' value='".($dis[$i] ?? "")."'></td>
            <td>
                <select name='type[]' class='form-control type_form'>
                    <option>-Select option-</option>";
                                        foreach ($optionsData as $opt) {
                                            $selected = (isset($type[$i]) && $type[$i] == $opt['pt_id']) ? "selected" : "";
                                            echo "<option value='".$opt['pt_id']."' $selected>".$opt['type']."</option>";
                                        }
                                        echo "</select></td>
                                        <td>";
                                        if (isset($type[$i]) && $type[$i] == 8) {
                                            echo "<input type='hidden' name='length[]' class='form-control' value='".($length[$i] ?? "50")."'>";
                                            echo showDropDown_master($link1, $dropdown[$i] ?? '');
                                        } else {
                                            //<input type='number' name='length[]' class='form-control' value='".($length[$i] ?? "")."'>
                                            echo "<input type='number' name='length[]' class='form-control' value='".($length[$i] ?? "")."'>";
                                        }
                                        echo "</td>
                               <td class='text-center'>
                               <input type='hidden' name='check[]' value='".$hiddenValue."'>
                               <input type='checkbox' class='check_box_hidden' value='".$hiddenValue."' ".$checkedAttr.">
                               </td>
                               </tr>";

                                    }
                                }
                                else {
                                    // fallback → start from 1 empty row
                                    echo "<tr>
        <td>1</td>
        <td><input type='text' class='form-control' name='param_name[]'></td>
        <td><input type='text' class='form-control' name='display_name[]'></td>
        <td>
            <select name='type[]' class='form-control type_form'>
                <option>-Select option-</option>";

                                    foreach ($optionsData as $opt) {
                                        echo "<option value='".$opt['pt_id']."'>".$opt['type']."</option>";
                                    }

                                    echo "  </select>
        </td>
        <td><input type='number' name='length[]' class='form-control'></td>
        <td class='text-center'>
        <input type='hidden' name='check[]' value='0'>
    <input type='checkbox' class='check_box_hidden' value='1'>
</td>
    </tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <button id="row" type="button" class="btn btn-danger" style="margin-top: 10px">Add Row</button>
                    </div>

                    <div class="text-center">
                        <button type="submit" style="text-transform: capitalize" name="<?=$operation?>" class="btn btn-success"><?=$operation?></button>
                        <a href="form_master.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>&id=<?=base64_encode($id_fms)?>" class="btn btn-warning">Back</a>
                    </div>

                </form>
            <?php } ?>
        </div>
    </div>
</div>
</div>

<div id="snackbar">This is a snackbar message</div>

<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
<div id="typeOptions" style="display:none;">
    <?=$option?>
</div>
<div id="customAlertContainer"></div>

</body>
</html>
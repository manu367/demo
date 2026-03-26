<?php
require_once("../includes/config.php");


set_exception_handler(function($e){
    if($e instanceof GlobalException){
        $msg = urlencode($e->getMessage());
        header("Location: fms_master.php?type=error&msg={$msg}");
        exit;
    }
});

function showTyepParameter($link){
    $option="";
    $result=mysqli_query($link, "SELECT * FROM parameter_type where status = '1'");
    if($result){
        while ($row=mysqli_fetch_assoc($result)){
            $option.="<option value='".$row['pt_id']."'>".$row['type']."</option>";
        }
    }
    return $option;
}
function loadFSM($link,$sql){
    $result=mysqli_query($link,$sql);
    if($result){
        $row=mysqli_fetch_assoc($result);
        return $row;
    }
    return false;
}


$operation=isset($_REQUEST['op'])?'update':'save';


$option=showTyepParameter($link1);
$formoperation=new FormOperations('','','',$link1);

$id_fms=0;
if(isset($_REQUEST['id'])){
    $id=$_REQUEST['id'];
    $load=loadFSM($link1,"SELECT * FROM fms_master where id=$id");
}

$msg=null;
if(isset($_POST['save']))
{

   $fmsid=$_POST['fmsid'];
   $frmName=$_POST['frm_name'];
   $frm_seq=$_POST['frm_seq'];

   // load fsm
   $fms_data=loadFSM($link1,"SELECT * FROM fms_master where id=$fmsid");

   // partname space = Part_SPACE_Name
    $paraName = json_encode(array_map(function($val){
        $val = trim($val);
        $val = preg_replace('/\s+/', '_', $val);
        return $val;}, $_POST['param_name']));
    $displayName = json_encode($_POST['display_name']);
    $type=json_encode($_POST['type']);
    $length=json_encode($_POST['length']);
    $check=json_encode($_POST['check']);

    // store all data in one unit
    $data=["fmsid"=>$fmsid,
            "formname"=>$frmName,
            'frm_seq'=>$frm_seq,
            "name"=>$paraName,
            "displayName"=>$displayName,
            "type"=>$type,
            "length"=>$length,
        "check"=>$check,
            ];


    $response=$formoperation->addForm($fmsid,$data,$_SESSION['userid']);

    $status_column=$formoperation->addColumnInTable($fms_data['table_name'],$_POST['param_name'],$_POST['type'],$_POST['length']);

    if($response){
        $msg="Form Added Successfully";
        $msgEnc = urlencode($msg);
        header("Location: fms_master.php?msg=$msgEnc");
        exit;
    }else{
        throw new Exception("some things is wrong");
    }
}


if(isset($_POST['update']))
{
    $fmsid      = $_POST['fmsid'];
    $formid=$_POST['formid'];
    $frmName    = $_POST['frm_name'];
    $paraName = json_encode(array_map(function($val){
        $val = trim($val);
        $val = preg_replace('/\s+/', '_', $val);
        return $val;}, $_POST['param_name']));
    $displayName = json_encode($_POST['display_name']);
    $type       = json_encode($_POST['type']);
    $length     = json_encode($_POST['length']);
    $frm_seq=$_POST['frm_seq'];
    $check=json_encode($_POST['check']);
    $data = [
            "fmsid"       => $fmsid,
            "formname"    => $frmName,
            "name"        => $paraName,
            "displayName" => $displayName,
            "type"        => $type,
            "length"      => $length,
            "frm_seq"=>$frm_seq,
            "check"=>$check,
    ];
    $fms_data_p=loadFSM($link1,"SELECT table_name FROM fms_master where id=$fmsid");

    $response = $formoperation->updateForm($formid,$fmsid, $data, $_SESSION['userid'],$fms_data_p['table_name']);

    if($response){
        $msg="Form Updated Successfully";
        $op     = $_REQUEST['op'] ?? '';
        $formid = $_REQUEST['formid'] ?? '';
        $formid=base64_encode($formid);
        $msgEnc = urlencode($msg);
        header("Location: create_form.php?op={$op}&msg={$msgEnc}&formid={$formid}");
        exit;
    } else {
        throw new Exception("Update failed");
    }
}

function loadForm($link1,$formid){
    $result=mysqli_query($link1,$formid);
    if($result){
        $row=mysqli_fetch_assoc($result);
        return $row;
    }
    return false;
}

if(isset($_REQUEST['formid'])){
    $formid=base64_decode($_REQUEST['formid']);
    $formid=intval($formid);
    $res=loadForm($link1,"SELECT * FROM form_master where id='$formid'");
    $load=loadFSM($link1,"SELECT * FROM fms_master where id='".$res['fms_id']."'");
    $id_fms=$load['id'];
    if($res){
    }else{
        throw new GlobalException("Some things is wrong");
    }
}

function decodeValueIntoArrar($value){
    $arr=explode("-",$value);
    return json_encode($arr);
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
<!--    alert box css-->
    <style>
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
            background: green;
            backdrop-filter: blur(8px);

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
            animation: progress 3s linear;
        }

        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
</head>
<body>
<?php
if(isset($_REQUEST['msg'])):?>
    <div id="errorPopup" class="toast">
        <span class="icon">⚠️</span>
        <span class="message"><?=$_REQUEST['msg']?></span>
    </div>
<?php endif; ?>
<script>
    $(document).ready(function(){
        let toast = $("#errorPopup");

        if(toast.length){
            setTimeout(() => {
                toast.addClass("show");
            }, 100);

            setTimeout(() => {
                toast.removeClass("show");
            }, 3000);
        }
    });
</script>

<div class="container-fluid">
    <div class="row content">
        <?php include("../includes/leftnav2.php"); ?>
        <div class="<?=$screenwidth?>">
            <h2 align="center" style="text-transform: capitalize">
                <i class="fa fa-users"></i> <?=$operation?> Form</h2><br/><br/>

            <form  name="frm1" id="frm1" class="form-horizontal" action="" method="post">
                <input type="text" id="fmsid" name="fmsid" value="<?=$load['id']?>" style="display: none;">
                <input type="text" id="formid" name="formid" value="<?=$res['id']?>" style="display: none;">
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
                    <table  width="100%" id="form_table" class="display" align="center" cellpadding="4" cellspacing="0" border="1">
                        <thead>
                        <tr class="<?=$tableheadcolor?>">
                            <th>#</th>
                            <th style="text-align: center">Name</th>
                            <th style="text-align: center">Display Name</th>
                            <th style="text-align: center">Type</th>
                            <th style="text-align: center">length</th>
                            <th style="text-align: center">Required</th>
                        </tr>
                        </thead>
                        <tbody id="addform">
                        <?php

                        $co     = json_decode($res['parameter_name'], true) ?? [];
                        $dis    = json_decode($res['display_name'], true) ?? [];
                        $type   = json_decode($res['type'], true) ?? [];
                        $length = json_decode($res['length'], true) ?? [];
                        $param_require=json_decode($res['param_require'],true) ?? [];
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
                                echo "<tr>
            <td>".($i+1)."</td>
            <td><input type='text' class='form-control' name='param_name[]' value='".($co[$i] ?? "")."'></td>
            <td><input type='text' class='form-control' name='display_name[]' value='".($dis[$i] ?? "")."'></td>
            <td>
                <select name='type[]' class='form-control type_form'>
                    <option>-Select option-</option>";
                                foreach ($optionsData as $opt) {
                                    $selected = (isset($type[$i]) && $type[$i] == $opt['pt_id']) ? "selected" : "";
                                    echo "<option value='".$opt['pt_id']."' $selected>".$opt['type']."</option>";
                                }
                                echo "</select></td><td><input type='number' name='length[]' class='form-control' value='".($length[$i] ?? "")."'></td><td class='text-center'><input type='hidden' name='check[]' value='".$hiddenValue."'><input type='checkbox' class='check_box_hidden' value='1' ".$checkedAttr."></td></tr>";

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
                    <button id="row" type="button" class="btn btn-danger" style="margin-top: 10px">Add Row</button>
                </div>

                <div class="text-center">
                    <button type="submit" style="text-transform: capitalize" name="<?=$operation?>" class="btn btn-success"><?=$operation?></button>
                    <a href="form_master.php?id=<?=base64_encode($id_fms)?>" class="btn btn-warning">Back</a>
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
<div id="typeOptions" style="display:none;">
    <?=$option?>
</div>
<div id="customAlertContainer"></div>
<script>
    function counterLe(value = '') {
        let count;
        if (value === '' || value === null || value === undefined) {
            count = 2;
        } else {
            const num = Number(value);
            if (isNaN(num)) {
                console.warn("Invalid value passed, defaulting to 2");
                count = 2;
            } else {
                count = num;
            }
        }
        return function () {
            return count++;
        };
    }

    document.addEventListener("DOMContentLoaded",function(){
        let count=counterLe('<?=$countleave===0?2:$countleave?>');
        $("#row").click(function () {
            let i=count();

            let options = $("#typeOptions").html(); // 🔥 yaha se uthao
            console.log(options);

            let newRow = `<tr>
     <td>${i}</td>
    <td><input type="text" name="param_name[]" class="form-control"></td>
    <td><input type="text" name="display_name[]" class="form-control"></td>
    <td>
        <select name="type[]" class="form-control">
            <option>-Select option-</option>
            ${options}
        </select>
    </td>
    <td><input type="number" name="length[]" class="form-control"></td>
<td class='text-center'><input type="hidden" name="check[]" value="0">
    <input type="checkbox" class="check_box_hidden" value="1">
</td>
</tr>`;
            checkboxvalue();
            $("#addform").append(newRow);
        });

        const form1=document.getElementById("frm1");
        form1.addEventListener("submit", function (e) {
            let isValid=true;
            const frm_name=document.getElementById("frm_name").value.trim();
            const fmsid=document.getElementById("fmsid").value.trim();
            const formid=document.getElementById("formid").value.trim();
            if(frm_name === null || frm_name === ""){
                showAlert("Please Fill Form Name", "error");
            }
            const param = document.querySelectorAll('input[name="param_name[]"]');
            const displayname=document.querySelectorAll("input[name='display_name[]']");
            const typeselect=document.querySelectorAll("select[name='type[]']");
            const inputlength=document.querySelectorAll("input[name='length[]']");
            param.forEach((input, index) => {
                if (input.value.trim() === "") {
                    showAlert(`Please Fill  Name ${index + 1}`, "error");
                    input.focus();
                    isValid = false;
                    return;
                }
            });

            displayname.forEach((input,index)=>{
                if(input.value.trim() === ""){
                    showAlert(`Disply Name must be enter ${index + 1}`, "error");
                    input.focus();
                    isValid = false;
                }
            });

            typeselect.forEach((input, index) => {
                if(input.value==='-Select option-'){
                    showAlert(`Please Select input type ${index + 1}`, "error");
                    input.focus();
                    isValid = false;
                }
            });

            inputlength.forEach((input, index) => {
                if (!input.value) {
                    showAlert(`input length Must be Enter ${index + 1}`, "error");
                    input.focus();
                    isValid = false;
                }
            });

            if(!isValid){
                e.preventDefault();
            }
        });
    });

// showAlert("Form submitted successfully!", "success");
// showAlert("Something went wrong!", "error");
// showAlert("Check your input!", "warning");

function showAlert(message, type = "success", duration = 3000) {
    const container = document.getElementById("customAlertContainer");
    const alert = document.createElement("div");
    alert.classList.add("alert-box", `alert-${type}`);
    alert.innerHTML = `
        <span>${message}</span>
        <span class="close-btn">&times;</span>
    `;
    container.appendChild(alert);
    alert.querySelector(".close-btn").addEventListener("click", () => {
        alert.remove();
    });
    setTimeout(() => {
        alert.remove();
    }, duration);
}


    const checkboxes = document.querySelectorAll("input[type='checkbox']");
    checkboxes.forEach(cb => {
        cb.checked = true;
        cb.value = 1;
        cb.addEventListener("change", function () {
            this.value = this.checked ? 1 : 0;
        });
    });
</script>

<script>
    // function appendHidden(form, name, value){
    //     let input = document.createElement("input");
    //     input.type = "hidden";
    //     input.name = name;
    //     input.className="check_box_hidden"
    //     input.value = value;
    //     form.appendChild(input);
    // }
    //
    // function appendArray(form, name, arr){
    //     arr.forEach(val => {
    //         appendHidden(form, name, val);
    //     });
    // }
    //
    //
    // document.getElementById("frm1").addEventListener("submit", function (e) {
    //
    // });

    function checkboxvalue(){
        document.querySelectorAll(".check_box_hidden").forEach((checkbox)=>{

            let hidden = checkbox.previousElementSibling;
            hidden.value = checkbox.checked ? 1 : 0;
            checkbox.addEventListener("change", function(){
                hidden.value = this.checked ? 1 : 0;
            });
        });
    }
    checkboxvalue();

</script>

</body>
</html>
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
    $id_fms=$load['id'];
}

$msg=null;

$pid=isset($_REQUEST['pid'])?$_REQUEST['pid']:'';
$hid=isset($_REQUEST['hid'])?$_REQUEST['hid']:'';


/**
 *  Workflow to Save form data
 *     Step 1 = get All Basic data ( FMsId , formid , old_column ,form_name, $from_sequeance , raw_data)
 *     Step 2 = loadFSM for checking fms id is correct or not
 *     Step 3 = parameter name and all important arument store as a json_encode format me
 *     Step 4 = all jsonformat data store in data[] unit (Working from data unit)
 *     Step 5 = we are called addForm() for store the data units
 *     Step 6 = addColumnInTable() are used to add Column into the table
 *     Step 7 = if everythings is good then return to the page and otherwise the redirect to
 *              fms_master with error message
 */
if(isset($_POST['save']))
{
   $fmsid=$_POST['fmsid'];
   $frmName=$_POST['frm_name'];
   $frm_seq=$_POST['frm_seq'];
    $id_fms=$fmsid;

   // load fsm
   $fms_data=loadFSM($link1,"SELECT * FROM fms_master where id=$fmsid");


   // partname space = Part_SPACE_Name
    $paraName = json_encode(array_map(function($val){
        $val = trim($val);
        $val = preg_replace('/\s+/', '_', $val);
        return strtolower($val);}, $_POST['param_name']));

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

    try{
        $status_column=$formoperation->addColumnInTable($fms_data['table_name'],$_POST['param_name'],$_POST['type'],$_POST['length']);
    }catch (Exception $e){
        throw new GlobalException($e->getMessage());
    }
    if($response){
        operationtracker($link1,$_SESSION['userid'],'Create Form',"Create Form".$data['formname'],'ADD',$_SERVER['REMOTE_ADDR']);
        $msg="Form Added Successfully";
        $msgEnc = urlencode($msg);

        header("Location: fms_master.php?pid=$pid&hid=$hid&msg=$msgEnc");
        exit;
    }else{
        throw new Exception("some things is wrong");
    }
}

/**
 *  Workflow to Update Form Data
 *     Step 1= get All Basic data ( FMsId , formid , old_column ,form_name, $from_sequeance , raw_data)
 *     Step 2= store in single unit
 *     Step 3= store all data FormSaveModel and use time check karna h ke
 *            old column is eual to new column or not
 *           Condition 1 = if not then add in new name in formSaveModel
 *           Condition 2 = else { flow are normally end}
 *    Step 4 = check fmsId is null || " " , if  yes then throw exceptions
 *    Step 5 = get all fms_data based om fms_id
 *    Step 6 = then update the form
 *             calling this function updateForm();
 *   Step 7 = if somethings is wrong in updateform() then throw the exception and redirect the page
 *            else same page with success message
 *
 */

if(isset($_POST['update']))
{
    $fmsid      = $_POST['fmsid'];
    $formid=$_POST['formid'];
    $old_column=$_POST['old_column'];
    $frmName    = $_POST['frm_name'];
    $frm_seq=$_POST['frm_seq'];

    $id_fms=$fmsid;

    $raw = $_POST['old_column'];
    $raw = trim($raw, "'");
    $old_column = json_decode($raw, true);
    $old_column=json_decode($old_column, true);


    $newData=[];
    $newColumnAddInDB=[];
    $newColumnAddInDB['formid']=$formid;
    $j=0;
    for($i=0;$i<count($_POST['param_name']);$i++){
        $newColumnName=$_POST['param_name'][$i];

        // space convert into the _ underscore
        $newColumnName = strtolower(trim($newColumnName));
        $newColumnName = preg_replace('/\s+/', '_', $newColumnName);


        $displayName=$_POST['display_name'][$i];
        $type=$_POST['type'][$i];
        $dropdown=0;
        if($type==='8'){
            $dropdown=$_POST['drop_down'][$j];
            $length='50';
            $j++;
        }else{
            $length=$_POST['length'][$i];
        }
        $length_1=(int)$length;
        if($length_1>255){
            $length='255';
        }
        $old_col=$old_column[$i]??null;
        $check=$_POST['check'][$i];

        if($old_col===null){
            $newColumnAddInDB['column'][]=new FormSaveModel($newColumnName,$displayName,$type,$check,$length??'50',null,$dropdown);
        }
        $newData[]=new FormSaveModel($newColumnName,$displayName,$type,$check,$length??'50',$old_col,$dropdown);
    }


    // new-data and old_data store in one unit here
    $data=[
            "fms_id"=>'',
            "formid"=>$formid,
            "frm_seq"=>$frm_seq,
            "new"=>$newData,
            "old_col"=>$old_column
    ];

    // here we can get the tble on the basic of ID
    if($fmsid===''){throw new GlobalException('id mismatch error');}
    $fms_data_p=loadFSM($link1,"SELECT table_name FROM fms_master where id=$fmsid");


    // if any extra new column add in form  then added in the db;

    if (!empty($newColumnAddInDB['column']) && count($newColumnAddInDB['column']) > 0) {
        $status = $formoperation->addnewColumnInDb($fms_data_p['table_name'], $newColumnAddInDB);
    }

    $response=null;
    try{
        $response = $formoperation->updateForm($formid,$fmsid, $data, $_SESSION['userid'],$fms_data_p['table_name']);
    }catch (Exception $e){
        $op     = $_REQUEST['op'] ?? '';
        $msg=$e->getMessage();
        $formid=base64_encode($formid);
        header("Location: create_form.php?pid={$pid}&hid={$hid}&op={$op}&type=error&msg={$msg}&formid={$formid}");
        exit;
    }

    if($response){
        $msg="Form Updated Successfully";
        operationtracker($link1,$_SESSION['userid'],'update_form ='.$data['formid'],"Update Form = ".$frmName,'UPDATE',$_SERVER['REMOTE_ADDR']);
        $op     = $_REQUEST['op'] ?? '';
        $formid = $_REQUEST['formid'] ?? '';
        $formid=base64_encode($formid);
        $msgEnc = urlencode($msg);
        header("Location: create_form.php?pid={$pid}&hid={$hid}&op={$op}&msg={$msgEnc}&formid={$formid}");
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

$isPermission=false;
if($operation==='save'){
    $isPermission=PermissionManager::checkaddRights($link1,$_SESSION['userid'],$_REQUEST['pid']);
}else{
    $isPermission=PermissionManager::checkEditRights($link1,$_SESSION['userid'],$_REQUEST['pid']);
}


$selectedBox=showDropDown_master($link1);
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
        let count=counterLe('<?=$countleave===0?2:$countleave+=1?>');
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
</script>

<script>
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
<script>
    // ye select box code
    document.querySelectorAll("input").forEach((cell) => {
        cell.style.textTransform = "capitalize";
    });
    document.querySelectorAll("select").forEach((cell) => {
        cell.style.textTransform = "capitalize";
    });
    const selectbox="<?=showDropDown_master($link1)?>";
    document.addEventListener("change", function(e){
        if (e.target.matches("select")) {
            if (e.target.value === '8') {
                const td = e.target.parentElement.nextElementSibling;
                const input = td.querySelector("input");

                if (input) {
                    input.value = 50;
                    input.type='hidden'
                }

                td.insertAdjacentHTML("beforeend", `<?=$selectedBox?>`);
            }
        }
    });
</script>

<script>
    function LNode(data){
        this.data=data;
    }
    function ConnectionStablish(){
        this.connection=new Map();
    }
    ConnectionStablish.prototype.addNode=function(key,node){
        if(!this.connection.has(key)){
            this.connection.add(key,new LNode(node));
        }
    }
    ConnectionStablish.prototype.deleteNode=function(key,node){
        if(this.connection.has(key)){
            this.connection.delete(key);
        }
    }
    ConnectionStablish.prototype.getConnection=function(key){
        if(!this.connection.has(key)){
            return this.connection.get(key);
        }
        return null;
    }

    ConnectionStablish.prototype.checkConnection=function(){}

    function DupblicationRemover(){
        this.old_col=null;
        this.dbCol=null;
        this.invalidInput=new Set();
        this.error=new Set();
        this.connection=new ConnectionStablish();
    }

    DupblicationRemover.prototype.dbParamterFetch=async function(){
        let url = `../pagination/table-column-data.php?fms_id=<?=$load['id']?>&formid=<?=$res['id']?>&column=${''}`;
        const response = await fetch(url);
        const data = await response.json();
        this.dbCol=await data;
    }

    DupblicationRemover.prototype.noromilizeFun=function(str){
        return str
            .toLowerCase()
            .replace(/\s+/g, '_'); // space -> underscore ( Candour Software => Candour_software)
    }

    DupblicationRemover.prototype.showSnackbar= function(input=null,msg='') {
        if(input!==null){
            input.style.border="2px solid red";
        }
        const snackbar = document.getElementById("snackbar");
        snackbar.textContent = msg;
        snackbar.classList.add("show");
        setTimeout(() => {
            snackbar.classList.remove("show");
        }, 5000);
    }

    DupblicationRemover.prototype.stopFormSubmit = function(){
        const self = this;
        document.querySelector("form").addEventListener("submit", function(e){
            if(self.error.size > 0){
                e.preventDefault();
                self.showSnackbar(null, "Fix errors before submitting");
            }
        });
    }

    DupblicationRemover.prototype.loadAllTable=function(){}

    DupblicationRemover.prototype.addForm=function (){
        if(this.dbCol!==null){
            this.dbCol.forEach((col)=>{
               this.invalidInput.add(col);
            });
        }
        // console.log(this.invalidInput);

        const set=this.invalidInput;
        const error=this.error;

        document.addEventListener("input", function(e) {
            if (e.target.matches("input[name='param_name[]']")) {
                // Remove special characters (keep letters + numbers only)
                let cleanValue = e.target.value.replace(/[^a-zA-Z0-9 ]/g, '');
                e.target.value = cleanValue;
                let value=DupblicationRemover.prototype.noromilizeFun(e.target.value);
                value=value.trim();
                if(set.has(value)){
                    error.add(e.target);
                    DupblicationRemover.prototype.showSnackbar(e.target,`Already exists ${e.target.value} in the table`);
                    DupblicationRemover.prototype.stopFormSubmit();
                }
                else{
                    error.delete(e.target);
                    e.target.style.border="0px solid";
                }

            }
            // console.log(error);
        });
    }

    DupblicationRemover.prototype.updateForm = function(){

        if(this.dbCol !== null){
            this.dbCol.forEach((col)=>{
                this.invalidInput.add(this.noromilizeFun(col));
            });
        }

        let old_col = document.getElementById("old_column");
        let old_col_value = JSON.parse(JSON.parse(old_col.value));

        const set = this.invalidInput;
        const error = this.error;
        const self = this;

        document.addEventListener("input", function(e){

            if (e.target.matches("input[name='param_name[]']")) {

                // Remove special characters (keep letters + numbers only)
                let cleanValue = e.target.value.replace(/[^a-zA-Z0-9 ]/g, '');
                e.target.value = cleanValue;
                let element = e.target;
                element.value = cleanValue; // ishke pass ab clean value h
                const newValue = self.noromilizeFun(element.value.trim());
                const oldValue = self.noromilizeFun(element.dataset.old ?? '');

                //case 1: same as old => always valid (manu_pathak = [manu_pathak]=old_col)
                if(newValue === oldValue){
                    error.delete(element);
                    element.style.border = "1px solid #ccc";
                    return;
                }

                // case 2: changed but already exists in DB - error
                if(set.has(newValue)){
                    error.add(element);
                    self.showSnackbar(element, `Already exists ${e.target.value} in the table`);
                    element.style.border = "2px solid red";
                }
                //  case 3: changed and unique -> valid
                else{
                    error.delete(element);
                    element.style.border = "1px solid #ccc";
                }
            }

            console.log([...error]);
        });
    };

    document.addEventListener("DOMContentLoaded", async function(){
        const operations="<?=isset($_REQUEST['op'])?$_REQUEST['op']:''?>";
        const validation=new DupblicationRemover();
        await validation.dbParamterFetch();

        if(operations===''){
            validation.addForm();
        }else{
            console.log(validation.dbCol);
            validation.updateForm();
        }
        validation.stopFormSubmit();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll("input[name=param_name]").forEach((inputs_paramter) => {
            console.log(inputs_paramter);
        });
    });
</script>
</body>
</html>
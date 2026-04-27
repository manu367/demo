<?php
require_once("../includes/config.php");
global $link1;

/**
 * Global Exception Handler
 * Purpose:
 * Catches all uncaught exceptions in the application and redirects
 * the user to a safe fallback page.
 *
 * Behavior:
 * Intercepts any unhandled exception
 * Extracts the exception message
 * Redirects the user to "add_dropdown.php" with error details
 *
 */
set_exception_handler(function ($exception) {
    header("location: add_dropdown.php?".$exception->getMessage());
    exit();
});

$op=isset($_REQUEST['op'])?$_REQUEST['op']:'';
$is_edit=$op==='edit'?true:false;

$drppdowm=new DropDownMaster($link1);

$paramter=[
        "pid"=>$_REQUEST['pid'],
        "hid"=>$_REQUEST['hid']
];

$flag=false;
$msg=null;
$operationSuccess=false;

/**
 * Handles the creation of a new dropdown entry via POST request.
 * Workflow:
 * Retrieve the current user's ID and IP address from session/server.
 * Pass all POST data to the dropdown handler (saveDropDownData).
 * Validate and process data through layered architecture:
 * - dropdownOperations: validates incoming data
 *       - dropdownService: applies business logic
 *             - dropdownRepo: performs database operations
 *  If the operation succeeds:
 * - Log the activity using operationtracker
 * - Redirect user with success message
 * If the operation fails:
 * - Set error flag and message
 * Error Handling:
 * Any exception is caught and wrapped into a GlobalException
 * Error details are passed as a query string
 * Dependencies: $_SESSION['userid'] $_SERVER['REMOTE_ADDR'] operationtracker() redirect() GlobalException
 * Notes:
 *  Ensure POST parameters are validated before processing
 * Avoid direct use of $_REQUEST where possible (prefer $_POST)
 */
if(isset($_POST['add'])){
    try{
        $drppdowm->setupdateBy_and_IP($_SESSION['userid'],$_SERVER['REMOTE_ADDR']);
        $flag=$drppdowm->saveDropDownData($_POST);
        if($flag){
            operationtracker($link1,$_SESSION['userid'],'Dropdown page',"dropdown added",'ADD DROPDOWN',$_SERVER['REMOTE_ADDR']);
            redirect('dropdown_master.php','DropDown saced successfully','success',['pid'=>$_REQUEST['pid'],'hid'=>$_REQUEST['hid']]);
        }else{
            $flag=false;
            $msg="Not Saved Data";
        }
    }catch (Exception $e){
        $paramter['type']='error';
        $paramter['msg']=$e->getMessage();
        $build=http_build_query($paramter);
        throw new GlobalException($build);
    }
}

/**
 * Handles the updation of a  dropdown entry via POST request.
 * Workflow :
 *    Retrieve the current user's ID and IP address from session/server.
 *    Pass all POST data to the dropdown handler (updateDateDropDownData).
 *   Validate and process data through layered architecture:
 *         dropdownOperations: validates incoming data
 *                 - dropdownService: applies business logic
 *                           - dropdownRepo: performs database operations
 *   If the operation succeeds:
 *  - Log the activity using operationtracker
 *  - Redirect user with success message
 *             If the operation fails:
 *              - Set error flag and message
 */
if(isset($_POST['update'])){
    $drppdowm->setupdateBy_and_IP($_SESSION['userid'],$_SERVER['REMOTE_ADDR']);
    $flag=$drppdowm->updateDateDropDownData($_POST);
    if($flag){
        $op_p=operationtracker($link1,$_SESSION['userid'],'dropdown',"Dropdown Updated",'UPDATE',$_SERVER['REMOTE_ADDR']);
        redirect('dropdown_master.php','DropDown savedy update successfully','success',['pid'=>$_REQUEST['pid'],'hid'=>$_REQUEST['hid']]);
    }
    else{
        $flag=false;
        $msg="Not Saved Data";
    }
}

/**
 * Check if both 'op' and 'dropdown' parameters are received
 * Work flow :
 *   Step 1 : Validate 'op' parameter , If empty, redirect with error message
 *   Step 2 : Validate 'dropdown' parameter , If empty, redirect with error message
 *   Step 3 : Decode dropdown ID from base64
 *   Step 4 : Fetch dropdown data using decoded ID
 */
if(isset($_REQUEST['op']) && $_REQUEST['dropdown']){
    // Trim values to avoid spaces issue
    $op = trim($_REQUEST['op']);
    $dropdown = trim($_REQUEST['dropdown']);

    if(empty($op)){
        redirect('dropdown_master.php','Opertions is not empty','error',['pid'=>$_REQUEST['pid'],'hid'=>$_REQUEST['hid']]);
    }
    if(empty($dropdown)){
        redirect('dropdown_master.php','dropdown is not empty','error',['pid'=>$_REQUEST['pid'],'hid'=>$_REQUEST['hid']]);
    }
    $dropdownId=base64_decode($_REQUEST['dropdown']);
    $edit_data=$drppdowm->getDropDownData($dropdownId);
}


/*
workflows
-> jab bhi koe user table_name select karega from the <select> se tab automatically submit hoga
-> then table value aayege wo value currenttale and previous table me set ho jayege
-> $isTableChanged change hone par he key and value reset ho jaaye otherwise form $formData['keyid'] and $formData['keyvalue'] se aaye data
*/
$currentTable = $_REQUEST['master_table'] ?? '';
$prevTable = $_REQUEST['prev_table'] ?? '';

$isTableChanged = ($currentTable !== $prevTable);

$formData = [];
if($is_edit && isset($edit_data)){
    $formData = $edit_data;
}else{
    $formData = $_REQUEST;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=siteTitle?></title>
    <meta http-equiv="refresh" content="1800">
    <link rel="shortcut icon" href="../images/titleimg.png" type="image/png">
    <script src="../js/jquery.js"></script>
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/abc.css" rel="stylesheet">
    <script src="../js/bootstrap.min.js"></script>
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script>
        $(document).ready(function(){
            $("#frm1").validate();
        });
    </script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <!-- Include Date Picker -->
    <script type="text/javascript" src="../js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-multiselect.css" type="text/css"/>
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
            max-width: 300px;

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
            animation: progress 50s linear;
        }

        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
</head>
<body>

<?php
if($flag){
    echo showToastUI($msg,'success');
}
?>
<div class="container-fluid">
    <div class="row content">
        <?php include("../includes/leftnav2.php"); ?>
        <div class="<?=$screenwidth?>">
            <h2 align="center"><i class="fa fa-users"></i> <?=$op==='edit'?'Update':'Add'?> DropDown</h2><br/><br/>
            <form  name="frm1" id="frm1" class="form-horizontal" action="" method="post">
                <input type="hidden" name="prev_table" value="<?= $_REQUEST['master_table'] ?? '' ?>">
                <?php
                if(isset($_REQUEST['dropdown'])){
                    echo '<input type="hidden" name="dropdown_id" value="'.base64_decode($_REQUEST['dropdown']).'">';
                }
                ?>
            <div class="form-group" id="page-wrap">
                <div class="form-group">
                        <div class="col-md-6">
                            <label for="user_id" class="col-md-6 control-label">
                                Name<span class="red_small">*</span>
                            </label>
                            <div class="col-md-6">
                                <input name="master_name" type="text" class="form-control"
                                       value="<?= $formData['master_name'] ?? '' ?>" required>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="username" class="col-md-6 control-label">Table Name<span class="red_small">*</span></label>
                            <div class="col-md-6">
                                <?= getMasterTablesDropdown(
                                        $link1,
                                        'master_table',
                                        $formData['master_table'] ?? ''
                                ) ?>
                            </div>
                        </div>
                    </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="user_id" class="col-md-6 control-label">
                            key<span class="red_small">*</span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            echo getMastertablekeys(
                                    $link1,
                                    'key_id',
                                    $formData['master_table'] ?? '',
                                    $isTableChanged ? '' : ($formData['key_id'] ?? '')
                            );
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="col-md-6 control-label">Value<span class="red_small">*</span></label>
                        <div class="col-md-6">
                            <?php
                            echo getMastertablekeys(
                                    $link1,
                                    'key_value',
                                    $formData['master_table'] ?? '',
                                    $isTableChanged ? '' : ($formData['key_value'] ?? '')
                            );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
                <div class="text-center">
                    <?php
                    if(!$operationSuccess){
                    ?>
                    <button type="submit" name="<?= $is_edit ? 'update' : 'add' ?>"  class="btn btn-success">
                        <?= $is_edit ? 'Update' : 'Add' ?>
                    </button>
                    <?php } ?>
                    <span class="btn btn-primary" onclick="window.location.href='dropdown_master.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>'">
                    <span id="operation_name">Cancel</span>
                </span>
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
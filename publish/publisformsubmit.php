<?php

require_once("../includes/common_classes.php");
require_once("../includes/common_function.php");
require_once("../security/dbh.php");
global $link1;
function fileUpload($field){
    if(isset($_FILES[$field]) && $_FILES[$field]['error'] == 0){
        $fileName = $_FILES[$field]['name'];
        $tmpName  = $_FILES[$field]['tmp_name'];
        $fileSize = $_FILES[$field]['size'];

        // File size limit (6MB)
        if($fileSize > 6 * 1024 * 1024){
            die("File too large");
        }

        // Current date folder (YYYY-MM-DD)
        $dateFolder = date("Y-m-d");
        $uploadDir = "./upload/" . $dateFolder . "/";

        // Create folder if not exists
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        // Unique file name
        $newName = time() . "_" . basename($fileName);
        $destination = $uploadDir . $newName;

        if(move_uploaded_file($tmpName, $destination)){
            return $destination;
        }
    }
    return false;
}

$formview=new FormView($link1);
if(isset($_POST['save'])){

    $pid=$_POST['pid'];
    $hid=$_POST['hid'];
    $formid=$_POST['formid'];

    $data=$formview->loadform(isset($_REQUEST['formid'])?$_REQUEST['formid']:'');

    $total=count(json_decode($data['parameter_name']));
    $parameter[]=json_decode($data['parameter_name'],true);

    $parameter_1=$parameter[0];

    $data_save=[];
    for($i=0;$i<$total;$i++){
        $fieldName = $parameter_1[$i];
        $uploadedFile = fileUpload($fieldName);
        if($uploadedFile !== false){
            $data_save[] = $uploadedFile;
        } else {
            $data_save[] = $_POST[$fieldName] ?? '';
        }
    }

    $parameter_1[]="updated_by";
    $parameter_1[]="updated_ip";

    $data_save[]='UNKNOWN USER';
    $data_save[]=$_SERVER['REMOTE_ADDR'];

    $tablename=getTablename($link1,isset($_POST['fmsid'])?$_POST['fmsid']:'');


    if($tablename){
        $save=null;
        try{
            $save=$formview->saveDataintable($tablename,$parameter_1,$data_save);
        }catch (Exception $e){
            $msg=$e->getMessage();
            header("location:publishqrcode.php?pid=$pid&hid=$hid&formid=$formid&uuid=&token=&type=error&msg=We couldn’t save your data in the $tablename table. $msg");
            exit();
        }
        if ($save) {
            operationtracker($link1,'ANNOYMOUS','publish_qr_code',"Add form data",'ADD',$_SERVER['REMOTE_ADDR']);
            header("location:publishqrcode.php?pid=$pid&hid=$hid&formid=$formid&uuid=&token=&type=success&msg=Your data has been successfully saved in the $tablename table.");
            exit();
        } else {
            header("location:publishqrcode.php?pid=$pid&hid=$hid&formid=$formid&uuid=&token=&type=error&msg=We couldn’t save your data in the $tablename table. Please try again.");
            exit();
        }
    }
    else{
        header("location:publishqrcode.php?pid=$pid&hid=$hid&formid=$formid&uuid=&token=&type=error&msg=Table not found. Please try again.");
        exit();
    }
}

function getTablename($link1, $fmsid) {
    if (!$link1 || !($link1 instanceof mysqli)) {
        return null;
    }
    if($fmsid===''){
        return null;
    }
    $fmsid = mysqli_real_escape_string($link1, $fmsid);
    $sql = "SELECT table_name FROM fms_master WHERE id='$fmsid'";
    $result = @mysqli_query($link1, $sql); // @ to suppress warnings
    if (!$result) {
        return null;
    }
    if (mysqli_num_rows($result) === 0) {
        return null;
    }
    $row = mysqli_fetch_assoc($result);
    if (!$row || !isset($row['table_name'])) {
        return null;
    }
    return $row['table_name'];
}


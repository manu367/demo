<?php
require_once("../includes/config.php");
global $link1;

function loadFSM($link,$sql){
    $result=mysqli_query($link,$sql);
    if($result){
        return mysqli_fetch_assoc($result);
    }
    return false;
}

$fsm_id   = base64_decode($_REQUEST['id']);
$form_id  = $_REQUEST['formid'];
$form_name= $_REQUEST['formname'];

$load = loadFSM($link1,"SELECT * FROM fms_master where id=$fsm_id");
$fmsname = $load['fmsname'];

// columns decode
$col = base64_decode($_REQUEST['col']);
$col = explode("-", $col);

// build proper JSON
$data_array = [];
for($i=0;$i<count($col);$i++){
    $data_array[$col[$i]] = "";
}

$col_header = json_encode($data_array, JSON_PRETTY_PRINT);

// FINAL STRUCTURE (curl format)
$data = "curl --location 'http://localhost/demo/fmsapi/fmsconnect.php'
--header 'Fms-Id: {$fsm_id}' 
--header 'Fms-Name: {$fmsname}' 
--header 'Form-Id: {$form_id}' 
--header 'Form-Name: {$form_name}' 
--header 'Content-Type: application/json' 
--data-raw '{$col_header}'";


echo $data;
// download
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="api_data'.date('d-m-Y-H-i-s').'.json"');

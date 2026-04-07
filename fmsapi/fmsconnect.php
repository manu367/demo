<?php

require_once("includes/secuity.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");




/*
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $accessToken=str_replace("Basic ",'',$accessToken);
    $accessToken=base64_decode($accessToken);
    $accessToken=explode(":",$accessToken);
    $vale=checkuser($link1,$accessToken[0],$accessToken[1]);

    if(!$vale){
        SendResponse::sendResponseData(false,"user not found");
    }
    if(!$fms_id){
        SendResponse::sendResponseData(false,"FMS Id not found");
    }
    if(!$fms_name){
        SendResponse::sendResponseData(false,"FMS name not found");
    }
    if(!$form_id){
        SendResponse::sendResponseData(false,"Form Id not found");
    }
    if (!$form_name){
        SendResponse::sendResponseData(false,"Form Name not found");
    }


    $column =BasicValidation($link1,$fms_id,$form_id,$fms_name,$form_name);

//    $column=removeColumn ($column,['id','created_date','update_date']);
    SendResponse::sendResponseData(true,$column['parameter_name']);

}
*/


$headers = getallheaders();

$accessToken = $headers['Authorization'] ?? null;
$fms_id      = $headers['Fms-Id'];
$fms_name    = $headers['Fms-Name'];
$form_id     = $headers['Form-Id'] ?? null;
$form_name   = $headers['Form-Name'] ?? null;
$link1=$conn->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawInput = file_get_contents("php://input");
    $body = json_decode($rawInput, true);
    /*	$headers = [];
    foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'HTTP_') === 0) {
        $headers[strtolower(str_replace('_', '-', substr($key, 5)))] = $value;
    } }
    */

    if (!$body) {
        SendResponse::sendResponseData(false,"Invalid JSON");
    }

    //------ basic validation in post
    $accessToken=str_replace("Basic ",'',$accessToken);
    $accessToken=base64_decode($accessToken);
    $accessToken=explode(":",$accessToken);
    $vale=checkuser($link1,$accessToken[0],$accessToken[1]);

    if(!$vale){
        SendResponse::sendResponseData(false,"user not found");
    }
    if(!$fms_id){
        SendResponse::sendResponseData(false,"FMS Id not found");
    }
    if(!$fms_name){
        SendResponse::sendResponseData(false,"FMS name not found");
    }
    if(!$form_id){
        SendResponse::sendResponseData(false,"Form Id not found");
    }
    if (!$form_name){
        SendResponse::sendResponseData(false,"Form Name not found");
    }


    $share_data =BasicValidation($link1,$fms_id,$form_id,$fms_name,$form_name);

    $jsonKeys=array_keys($body);

    $validate=validateColumn($jsonKeys,$share_data['parameter_name']);


    if(!$validate['status']){
        SendResponse::sendResponseData(false,"Column are not correct");
    }

    $column_store=$validate['data'];
    $share_data=storeDataUnit($share_data);

    if(saveData($link1,$body,$share_data,$form_id)){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        SendResponse::sendResponseData(true,"Successfully Saved Data, USER_IP= ".$ip);
    }
}


//
//$customer_name = $body['customer_name'] ?? null;
//$email         = $body['email'] ?? null;
//$phone_number  = $body['phone_number'] ?? null;
//
//
//if (!$accessToken || !$fms_id || !$form_id) {
//    echo json_encode([
//        "status" => false,
//        "data" => "Missing required headers"
//    ]);
//    exit;
//}
//
//if (!$customer_name || !$email || !$phone_number) {
//    echo json_encode([
//        "status" => false,
//        "data" => "Missing required body fields"
//    ]);
//    exit;
//}
//
//$response = [
//    "status" => true,
//    "data" => [
//        [
//            "customer_name" => $customer_name,
//            "email" => $email,
//            "phone_number" => $phone_number,
//            "fms_id" => $fms_id,
//            "form_id" => $form_id
//        ]
//    ]
//];
//
//echo json_encode($response, JSON_PRETTY_PRINT);


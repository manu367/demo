<?php
require_once("includes/secuity.php");
global $conn;

header("Content-Type: application/json");

$headers = getallheaders();
$accessToken = $headers['Authorization'] ?? null;
$link1=$conn->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accessToken=str_replace("Basic ",'',$accessToken);
    $accessToken=base64_decode($accessToken);
    $accessToken=explode(":",$accessToken);
    $vale=checkuser_1($link1,$accessToken[0],$accessToken[1]);

    if(!$vale){
        SendResponse::sendResponseData(false,"user not found");
    }

    $data=FmsOperations::getAllFMS($conn->getConnection(),$vale);
    SendResponse::sendResponseData(true,$data);
}
<?php
require_once("../includes/config.php");

header('Content-Type: application/json');

if (!isset($_REQUEST['formid']) || !isset($_REQUEST['fmsid'])) {
    echo json_encode([
        "status" => false,
        "message" => "Missing parameters"
    ]);
    exit;
}

$formid = mysqli_real_escape_string($link1, $_REQUEST['formid']);
$fmsid = mysqli_real_escape_string($link1, $_REQUEST['fmsid']);

$sql = "SELECT * FROM form_master 
        WHERE id = '$formid' AND fms_id = '$fmsid' LIMIT 1";

$result = mysqli_query($link1, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo json_encode([
        "status" => false,
        "message" => "No data found"
    ]);
    exit;
}

$data = mysqli_fetch_assoc($result);

// 🔥 decode JSON fields (important)
$data['parameter_name'] = json_decode($data['parameter_name'], true);
echo json_encode([
    "status" => true,
    "data" => $data['parameter_name']
]);
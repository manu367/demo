<?php
require_once("../includes/config.php");
global $link1;
header("Content-Type: application/json");


if(isset($_GET['chart_type'])){

    if ($_REQUEST['chart_type'] === "") {
        responseSender(false, "Empty chart type");
    }

    $chartType = mysqli_real_escape_string($link1, $_GET['chart_type']);

    $sql = "SELECT operations FROM charts_master WHERE chart_type='$chartType' AND status='1'";
    $result = mysqli_query($link1, $sql);

    if (!$result) {
        responseSender(false, "Database error: " . mysqli_error($link1));
    }

    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        responseSender(false, "No chart found");
    }

// 🔹 Decode operations
    $operations = json_decode($row['operations'], true);

    if (!is_array($operations) || count($operations) === 0) {
        responseSender(false, "No operations found");
    }

// 🔹 Step 2: Fetch operation details
    $finalData = [];

    foreach ($operations as $opId) {

        $opId = mysqli_real_escape_string($link1, $opId);

        $sql = "SELECT id, operation FROM chart_operation WHERE id='$opId' AND status='1'";
        $res = mysqli_query($link1, $sql);

        if ($res && $row = mysqli_fetch_assoc($res)) {
            $finalData[] = $row;
        }
    }

// 🔹 Final response
    responseSender(true, $finalData);

}


function responseSender($status, $data) {
    echo json_encode([
        "status" => $status,
        "data" => $data
    ]);
    exit();
}
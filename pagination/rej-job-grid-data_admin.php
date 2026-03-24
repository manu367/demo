<?php
require_once("../includes/config.php");

// get status
$arrstatus = getJobStatus($link1);

$requestData = $_REQUEST;
$date_range = !empty($_REQUEST['daterange']) ? explode(" - ", $_REQUEST['daterange']) : [];

$columns = array(
    0 => 'job_id',
    1 => 'job_no',
    2 => 'imei',
    3 => 'product_id',
    4 => 'brand_id',
    5 => 'model',
    6 => 'open_date',
    7 => 'close_date',
    8 => 'customer_name',
    9 => 'status'
);

// Base query
$sql = "SELECT * FROM jobsheet_data WHERE status IN ('1','10') AND pen_status='2'";

// Apply date filter if provided
if (!empty($date_range)) {
    $start_date = date('Y-m-d', strtotime($date_range[0]));
    $end_date = date('Y-m-d', strtotime($date_range[1]));
    $sql .= " AND open_date BETWEEN '$start_date' AND '$end_date'";
}

// Get total records
$query = mysqli_query($link1, $sql) or die("Error fetching total records");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

// Apply search filter
if (!empty($requestData['search']['value'])) {
    $searchValue = mysqli_real_escape_string($link1, $requestData['search']['value']);
    $sql .= " AND (job_no LIKE '$searchValue%' OR imei LIKE '$searchValue%')";
}

$query = mysqli_query($link1, $sql) or die("Error fetching filtered records");
$totalFiltered = mysqli_num_rows($query);

// Apply ordering & pagination
$orderColumnIndex = intval($requestData['order'][0]['column']);
$orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'job_id';
$orderDir = ($requestData['order'][0]['dir'] === 'asc') ? 'ASC' : 'DESC';
$start = intval($requestData['start']);
$length = intval($requestData['length']);

$sql .= " ORDER BY $orderColumn $orderDir LIMIT $start, $length";
$query = mysqli_query($link1, $sql) or die("Error fetching paginated records");

$data = array();
$j = 1;
while ($row = mysqli_fetch_assoc($query)) {
    $nestedData = array();
    $ack = "<div align='center'><a href='reassigen_jobs_admin.php?job_no=".$row['job_no']."' title='go to Assign'><i class='fa fa-wrench fa-lg faicon'></i></a></div>";

    $nestedData[] = $j;
    $nestedData[] = $row["job_no"];
    $nestedData[] = $row["customer_id"];
    $nestedData[] = $row["customer_name"];
    $nestedData[] = getAnyDetails($row["brand_id"], "brand", "brand_id", "brand_master", $link1);
    $nestedData[] = getAnyDetails($row["product_id"], "product_name", "product_id", "product_master", $link1);
    $nestedData[] = $row["model"];
    $nestedData[] = $row["imei"];
    $nestedData[] = $row["open_date"];
    $nestedData[] = $row["close_date"];

    if(isset($arrstatus[$row["sub_status"]][$row["status"]])) {
        $nestedData[] = $arrstatus[$row["sub_status"]][$row["status"]];
    } else {
        $nestedData[] = getAnyDetails($row["status"], "display_status", "status_id", "jobstatus_master", $link1);
    }

    $nestedData[] = $ack;
    $data[] = $nestedData;
    $j++;
}

$json_data = array(
    "draw" => intval($requestData['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
);

echo json_encode($json_data);
?>

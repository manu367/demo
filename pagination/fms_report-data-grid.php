<?php

require_once("../includes/config.php");
global $link1;

$draw   = $_REQUEST['draw'] ?? 1;
$start  = $_REQUEST['start'] ?? 0;
$length = $_REQUEST['length'] ?? 10;
$searchValue = $_REQUEST['search']['value'] ?? "";

$tablename = $_REQUEST['tableaname'] ?? "";


$remove = ['id', 'created_date', 'update_date', 'updated_by', 'updated_ip'];


$where = "";

if (!empty($searchValue)) {
    $searchValue = mysqli_real_escape_string($link1, $searchValue);
    $where = " WHERE ";

    // dynamic search across columns
    $colRes = mysqli_query($link1, "SHOW COLUMNS FROM `$tablename`");
    $searchParts = [];

    while ($col = mysqli_fetch_assoc($colRes)) {
        if (!in_array($col['Field'], $remove)) {
            $searchParts[] = "`".$col['Field']."` LIKE '%$searchValue%'";
        }
    }

    if (!empty($searchParts)) {
        $where .= implode(" OR ", $searchParts);
    } else {
        $where = "";
    }
}
//var_dump("SELECT COUNT(*) as c FROM `$tablename`");exit();
$totalRes = mysqli_query($link1, "SELECT COUNT(*) as c FROM `$tablename`");
$totalData = mysqli_fetch_assoc($totalRes)['c'] ?? 0;



// Filtered count
$filteredRes = mysqli_query($link1, "SELECT COUNT(*) as c FROM `$tablename` $where");
$totalFiltered = mysqli_fetch_assoc($filteredRes)['c'] ?? 0;


$sql = "SELECT * FROM `$tablename` $where LIMIT $start, $length";
$res = mysqli_query($link1, $sql);

$data = [];
$serial = $start + 1;

while ($row = mysqli_fetch_assoc($res)) {

    $rowData = [];
    $rowData[] = $serial++; // serial number

    foreach ($row as $col => $value) {
        if (!in_array($col, $remove)) {
            $rowData[] = $value;
        }
    }

    $data[] = $rowData;
}

// Output
echo json_encode([
    "draw" => intval($draw),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
]);
exit;
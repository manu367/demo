<?php
require_once("../includes/config.php");

/* Debugging ON (optional) */
ini_set('display_errors', 1);
error_reporting(E_ALL);

/* Request Data */
$requestData = $_REQUEST;

/* ------------------ DATE RANGE ------------------ */
if (!empty($_REQUEST['daterange']) && strpos($_REQUEST['daterange'], " - ") !== false) {
    $date_range = explode(" - ", $_REQUEST['daterange']);
    $daterange = "sale_date >= '" . $date_range[0] . "' AND sale_date <= '" . $date_range[1] . "'";
} else {
    $daterange = "1=1";
}

/* ------------------ DataTables Required Params ------------------ */
$orderColumn = $requestData['order'][0]['column'] ?? 0;
$orderDir    = $requestData['order'][0]['dir'] ?? "asc";
$start       = $requestData['start'] ?? 0;
$length      = $requestData['length'] ?? 10;

/* Column Mapping */
$columns = array(
    0 => 'from_location',
    1 => 'to_location',
    2 => 'challan_no',
    3 => 'sale_date',
    4 => 'status'
);

/* ------------------ TOTAL RECORDS ------------------ */
$sql = "
    SELECT from_location,to_location,status,challan_no,sale_date
    FROM billing_master 
    WHERE from_location = '" . $_SESSION['asc_code'] . "'
      AND $daterange
      AND (po_type = 'Sale Return' OR po_type = 'Stock Transfer')
";

$query = mysqli_query($link1, $sql) or die("SQL ERROR: " . mysqli_error($link1));
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

/* ------------------ SEARCH FILTER ------------------ */
$sql = "
    SELECT from_location,to_location,status,challan_no,sale_date
    FROM billing_master 
    WHERE from_location = '" . $_SESSION['asc_code'] . "'
      AND $daterange
      AND (po_type = 'Sale Return' OR po_type = 'Stock Transfer')
";

if (!empty($requestData['search']['value'])) {
    $search = mysqli_real_escape_string($link1, $requestData['search']['value']);
    $sql .= " AND ( challan_no LIKE '$search%' OR from_location LIKE '$search%' )";
}

$query = mysqli_query($link1, $sql) or die("SQL ERROR: " . mysqli_error($link1));
$totalFiltered = mysqli_num_rows($query);

/* ------------------ ORDER + LIMIT ------------------ */
$sql .= " ORDER BY " . $columns[$orderColumn] . " $orderDir LIMIT $start, $length";

$query = mysqli_query($link1, $sql) or die("SQL ERROR: " . mysqli_error($link1));

/* ------------------ OUTPUT DATA ------------------ */
$data = array();
$j = 1;

while ($row = mysqli_fetch_array($query)) {

    $nestedData = array();

    $nestedData[] = $j;
    $nestedData[] = getAnyDetails($row["from_location"], "locationname", "location_code", "location_master", $link1);
    $nestedData[] = getAnyDetails($row["to_location"], "locationname", "location_code", "location_master", $link1);
    $nestedData[] = $row['challan_no'];
    $nestedData[] = dt_format($row['sale_date']);
    $nestedData[] = getdispatchstatus($row["status"]);

    /* --- Clean single-line HTML buttons (NO \r\n \n \t) --- */

    // View Button
    $viewBtn = "<div align='center'><a href='invoice_challan_srn.php?refid="
        . base64_encode($row['challan_no'])
        . "&daterange=" . $_REQUEST['daterange']
        . "' title='View'><i class='fa fa-eye fa-lg faicon'></i></a></div>";

    // Print Button
    $printBtn = "<div align='center'><a href='invoice_challan_print_srn.php?id="
        . base64_encode($row['challan_no'])
        . "' target='_blank' title='Print'><i class='fa fa-print fa-lg faicon'></i></a></div>";

    // Truck / Courier Button
    if ($row["status"] == 2 || $row["status"] == 3) {
        $courierBtn = "<div align='center'><a href='#' onClick=\"openCourierModel('"
            . $row["challan_no"] . "')\" title='Update Courier'><i class='fa fa-truck fa-lg faicon'></i></a></div>";
    } else {
        $courierBtn = "";
    }

    // Final buttons
    $nestedData[] = $viewBtn;
    $nestedData[] = $printBtn;
    $nestedData[] = $courierBtn;

    $data[] = $nestedData;
    $j++;
}

/* ------------------ JSON OUTPUT ------------------ */
$json_data = array(
    "draw" => intval($requestData['draw'] ?? 1),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
);

echo json_encode($json_data);
?>

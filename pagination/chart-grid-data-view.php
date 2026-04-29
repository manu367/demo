<?php
require_once("../includes/config.php");

$draw   = $_REQUEST['draw'] ?? 1;
$start  = $_REQUEST['start'] ?? 0;
$length = $_REQUEST['length'] ?? 10;
$searchValue = $_REQUEST['search']['value'] ?? "";

$pid = $_REQUEST['pid'] ?? null;
$hid = $_REQUEST['hid'] ?? null;
$fms_id = $_REQUEST['fms_id'] ?? null;

$userid = $_SESSION['userid'] ?? 0;

/* Column mapping */
$columns = [
    0 => 'id',
    1 => 'title',
    2 => 'x_axis',
    3 => 'y_axis',
    4 => 'x_axis_param1',
    5 => 'x_axis_param2',
    6 => 'chart_status'
];

/* Ordering */
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderColumn = $columns[$orderColumnIndex] ?? 'id';
$orderDir = ($_POST['order'][0]['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

/* WHERE condition */
$where = "WHERE 1=1";

/* Filter by fms_id */
if (!empty($fms_id)) {
    $fms_id = mysqli_real_escape_string($link1, $fms_id);
    $where .= " AND fms_id = '$fms_id'";
}

/* Search */
if (!empty($searchValue)) {
    $searchValue = mysqli_real_escape_string($link1, $searchValue);
    $where .= " AND (
        title LIKE '%$searchValue%' OR
        x_axis LIKE '%$searchValue%' OR
        y_axis LIKE '%$searchValue%' OR
        x_axis_param1 LIKE '%$searchValue%' OR
        x_axis_param2 LIKE '%$searchValue%'
    )";
}

/* Total records */
$totalQuery = "SELECT COUNT(*) as total FROM dashboard_master";
$totalRes = mysqli_query($link1, $totalQuery);
$totalData = mysqli_fetch_assoc($totalRes)['total'] ?? 0;

/* Filtered records */
$filteredQuery = "SELECT COUNT(*) as total FROM dashboard_master $where";
$filteredRes = mysqli_query($link1, $filteredQuery);
$totalFiltered = mysqli_fetch_assoc($filteredRes)['total'] ?? 0;

/* Main query */
$sql = "
    SELECT *
    FROM dashboard_master
    $where
    ORDER BY $orderColumn $orderDir
    LIMIT $start, $length
";

$res = mysqli_query($link1, $sql);

$data = [];
$serial = $start + 1;

while($row = mysqli_fetch_assoc($res)){

    if($row['chart_status']==1){
        $status='<span style="color:green;font-weight:bold;">Active</span>';
    }else{
        $status='<span style="color:#b10b0b;font-weight:bold;">Deactive</span>';
    }
    $data[] = [
        $serial++,
        $row['title'],
        $row['x_axis'],
        $row['y_axis'],
        $row['x_axis_param1'],
        $row['x_axis_param2'],
        $status,
        PermissionManager::checkViewRights($link1,$_SESSION['userid'],$pid)?
            '<div style="display: flex; flex-direction: column; gap: 6px;">
<a href="gui_master_2.php?pid='.$pid.'&hid='.$hid.'&id='.($fms_id).'&chartid='.$row['id'].'"" class="btn btn-sm btn-primary">View</a>
</div>':'',
    ];
}

/* Output */
echo json_encode([
    "draw" => intval($draw),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
]);



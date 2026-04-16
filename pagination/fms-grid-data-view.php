<?php
require_once("../includes/config.php");
$draw   = $_POST['draw'] ?? 1;
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$searchValue = $_POST['search']['value'] ?? "";

$pid=$_POST['pid'] ?? null;
$hid=$_POST['hid'] ?? null;

$userid=$_SESSION['userid'];


$columns = [
    0 => 'fm.fmsname',
    1 => 'fm.details',
];

$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderColumn = $columns[$orderColumnIndex] ?? 'userloginid';
$orderDir = ($_POST['order'][0]['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

$where = " WHERE afs.userid='$userid' AND afs.status='1' ";
if($searchValue != ""){
    $searchValue = mysqli_real_escape_string($link1,$searchValue);
    $where .= " AND (
        fm.fmsname LIKE '%$searchValue%' OR
        fm.details LIKE '%$searchValue%'
    )";
}


$totalRes = mysqli_query($link1,"
    SELECT COUNT(*) c 
    FROM fms_master fm
    LEFT JOIN access_fms afs ON afs.fmsid = fm.id
    WHERE afs.userid='$userid' AND afs.status='1'
");
$totalData = mysqli_fetch_assoc($totalRes)['c'];

$filteredRes = mysqli_query($link1,"
    SELECT COUNT(*) c 
    FROM fms_master fm
    LEFT JOIN access_fms afs ON afs.fmsid = fm.id
    $where
");
$totalFiltered = mysqli_fetch_assoc($filteredRes)['c'];


//SELECT fm.* ,afs.* FROM fms_master AS fm LEFT JOIN access_fms AS afs ON afs.fmsid = fm.id WHERE afs.userid='$userid' and afs.status='1'
$sql = "
    SELECT fm.* 
    FROM fms_master fm
    LEFT JOIN access_fms afs ON afs.fmsid = fm.id
    $where
    ORDER BY $orderColumn $orderDir 
    LIMIT $start,$length
";

$res = mysqli_query($link1,$sql);

$data = [];
$serial = $start + 1;
//current_timestamp()
while($row = mysqli_fetch_assoc($res)){

    if($row['status']==1){
        $status='<span style="color:green;font-weight:bold;">Active</span>';
    }else{
        $status='<span style="color:#b10b0b;font-weight:bold;">Deactive</span>';
    }
    $data[] = [
        $serial++,
        $row['fmsname'],
        $row['details'],
        $row['created_at'],
        $row['updated_at'],
        $status,
        PermissionManager::checkViewRights($link1,$_SESSION['userid'],$pid)?'<div style="display: flex; flex-direction: column; gap: 6px;">
<a href="fms_view_form.php?pid='.$pid.'&hid='.$hid.'&id='.base64_encode($row['id']).'" class="btn btn-sm btn-primary">View</a>
<a href="fms_report.php?pid='.$pid.'&hid='.$hid.'&id='.base64_encode($row['id']).'" class="btn btn-sm btn-primary">Reports</a>
</div>':'',
    ];
}

echo json_encode([
    "draw"=>intval($draw),
    "recordsTotal"=>intval($totalData),
    "recordsFiltered"=>intval($totalFiltered),
    "data"=>$data
]);
exit;
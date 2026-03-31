<?php
require_once("../includes/config.php");

$draw   = $_POST['draw'] ?? 1;
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$searchValue = $_POST['search']['value'] ?? "";

$pid=$_POST['pid'] ?? null;
$hid=$_POST['hid']??null;
$userid=$_SESSION['userid'];

$columns = [
    0 => '',
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
        PermissionManager::checkViewRights($link1,$_SESSION['userid'],$pid)?'<a href="add_fms_master.php?pid='.$pid.'&hid='.$hid.'&op=edit&id='.base64_encode($row['id']).'" class="btn btn-sm btn-primary">View</a>':'',
        PermissionManager::checkViewRights($link1, $_SESSION['userid'], $pid)
            ? '<a href="form_master.php?pid='.$pid.'&hid='.$hid.'&id='.base64_encode($row['id']).'" class="btn btn-sm btn-primary">Create</a>'
            : '',
        PermissionManager::checkViewRights($link1,$_SESSION['userid'],$pid)?'<a href="clone_fms_master.php?pid='.$pid.'&hid='.$hid.'&id='.($row['id']).'" class="btn btn-sm btn-primary">Clone</a>':'',
    ];
}

echo json_encode([
    "draw"=>intval($draw),
    "recordsTotal"=>intval($totalData),
    "recordsFiltered"=>intval($totalFiltered),
    "data"=>$data
]);
exit;
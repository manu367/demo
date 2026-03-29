<?php
require_once("../includes/config.php");
$draw   = $_POST['draw'] ?? 1;
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$searchValue = $_POST['search']['value'] ?? "";
$fmsid= $_REQUEST['id'] ?? "";

$pid= $_REQUEST['pid'] ?? "";
$hid= $_REQUEST['hid'] ?? "";


$columns = [
    0 => 'form_name',
    1 => 'displayname',
];

$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderColumn = $columns[$orderColumnIndex] ?? '';
$orderDir = ($_POST['order'][0]['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

$where = "";
if($searchValue != ""){
    $searchValue = mysqli_real_escape_string($link1,$searchValue);
    $where = " WHERE fms_id=$fmsid AND
        form_name LIKE '%$searchValue%' OR
        display_name LIKE '%$searchValue%'
      ";
}else{
    $where = " WHERE fms_id=$fmsid";
}



$totalRes = mysqli_query($link1,"SELECT COUNT(*) c FROM form_master where fms_id =$fmsid");

$totalData = mysqli_fetch_assoc($totalRes)['c'];
$filteredRes = mysqli_query($link1,"SELECT COUNT(*) c FROM form_master $where");

$totalFiltered = mysqli_fetch_assoc($filteredRes)['c'];


$sql = " SELECT * FROM form_master $where ORDER BY $orderColumn $orderDir LIMIT $start,$length";

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
        $row['form_name'],
        $row['display_name'],
        $status,
        PermissionManager::checkViewRights($link1,$_SESSION['userid'],$pid)?'<a href="form_view.php?pid='.$pid.'&hid='.$hid.'&formid='.base64_encode($row['id']).'" class="btn btn-sm btn-primary">View</a>':'',
        PermissionManager::checkViewRights($link1,$_SESSION['userid'],$pid)?'<a href="form_upload.php?pid='.$pid.'&hid='.$hid.'&formid='.($row['id']).'" class="btn btn-sm btn-success">Upload</a>':'',
        PermissionManager::checkViewRights($link1,$_SESSION['userid'],$pid)?'<button onclick="showApi(this)" data-fromid="'.$row['id'].'" data-formName="'.$row['form_name'].'" data-column="'.implode('-',json_decode($row['parameter_name'])).'"  class="btn btn-sm btn-danger">API</button>':'',
    ];
}

echo json_encode([
    "draw"=>intval($draw),
    "recordsTotal"=>intval($totalData),
    "recordsFiltered"=>intval($totalFiltered),
    "data"=>$data
]);
exit;
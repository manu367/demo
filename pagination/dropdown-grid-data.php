<?php
require_once("../includes/config.php");
$draw   = $_POST['draw'] ?? 1;
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$searchValue = $_POST['search']['value'] ?? "";


$pid= $_REQUEST['pid'] ?? "";
$hid=$_REQUEST['hid'] ?? "";


$columns = [
    0 => 'master_name',
    1 => 'master_table',
];

$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderColumn = $columns[$orderColumnIndex] ?? '';
$orderDir = ($_POST['order'][0]['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

$where = "";
if($searchValue != ""){
    $searchValue = mysqli_real_escape_string($link1,$searchValue);
    $where = " WHERE 
        master_name LIKE '%$searchValue%' OR
        master_table LIKE '%$searchValue%' and status=1
      ";
}



$totalRes = mysqli_query($link1,"SELECT COUNT(*) c FROM dropdown_master where status='1'");

$totalData = mysqli_fetch_assoc($totalRes)['c'];
$filteredRes = mysqli_query($link1,"SELECT COUNT(*) c FROM dropdown_master $where");

$totalFiltered = mysqli_fetch_assoc($filteredRes)['c'];


$sql = " SELECT * FROM dropdown_master $where ORDER BY $orderColumn $orderDir LIMIT $start,$length";

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
        $row['master_name'],
        $row['master_table'],
        $status,
        true?'<a href="add_dropdown.php?pid='.$pid.'&hid='.$hid.'&op=edit&dropdown='.base64_encode($row['id']).'" class="btn btn-sm btn-primary">Edit</a>':'',
    ];
}

echo json_encode([
    "draw"=>intval($draw),
    "recordsTotal"=>intval($totalData),
    "recordsFiltered"=>intval($totalFiltered),
    "data"=>$data
]);
exit;
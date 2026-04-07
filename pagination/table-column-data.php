<?php
require_once("../includes/config.php");
global $link1;

$formid = mysqli_real_escape_string($link1, $_REQUEST['formid']);
$fmsid = mysqli_real_escape_string($link1, $_REQUEST['fms_id']);
$column=mysqli_real_escape_string($link1, $_REQUEST['column']);

$sql = "select * from fms_master where id='$fmsid'";

$result = mysqli_query($link1, $sql);
if(!$result || !mysqli_num_rows($result)===0){
    echo ['status'=>false,'msg'=>'No data'];
}
$row=mysqli_fetch_assoc($result);

$table = $row['table_name'];

$sql2 = "DESCRIBE `$table`";
$result2 = mysqli_query($link1, $sql2);

$columns = [];
while($col = mysqli_fetch_assoc($result2)){
    $columns[] = $col['Field'];
}

echo json_encode($columns);exit();
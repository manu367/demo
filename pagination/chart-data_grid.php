<?php
require_once("../includes/config.php");
if(isset($_REQUEST['chart'])){
    $sql="select id,chart_name,chart_type,attribute,operations from charts_master where status='1'";
    $result=mysqli_query($link1,$sql);
    if(!$result && mysqli_num_rows($result)==0){
       echo "No result";
    }
    $data=[];
    while ($row=mysqli_fetch_assoc($result)) {
        $data[]=$row;
    }
    echo json_encode($data);
}



header("Content-type: application/json");
exit();
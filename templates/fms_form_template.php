<?php
require_once("../includes/config.php");
if(isset($_REQUEST['tabname'])){
    $tablename = $_REQUEST['tabname'];
    $result=mysqli_query($link1,"DESCRIBE $tablename");
    $column=[];
    if($result){
        while($row=mysqli_fetch_assoc($result)){
            $column[]=$row['Field'];
        }
    }
}

function dynamicColumn($column){
    $remove = ['id','created_date','update_date','updated_by','updated_ip'];
    $column = array_values(array_filter($column, function($col) use ($remove) {
        return !in_array($col, $remove);
    }));

    $row = "";
    for($i = 0; $i < count($column); $i++){
        $value = str_replace("_"," ", $column[$i]);
        $row .= '<th>'.$value.'</th>';
    }
    $row .= "";
    return $row;
}
?>

<table width="100%" border="1" cellpadding="5" cellspacing="0">
    <tr style="background-color: green;color: white">
    <?= dynamicColumn($column)?>
    </tr>
</table>

<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=fms_report_".date('Y-m-d').".xls");
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');

?>

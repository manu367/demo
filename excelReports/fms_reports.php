<?php
require_once("../includes/config.php");
global $link1;

$keys = [];
$report_data = [];
$flag = false;

if(isset($_REQUEST['tab']) && isset($_REQUEST['time'])){

    $tablename = base64_decode($_REQUEST['tab']);
    $date = explode(" - ", $_REQUEST['time']);

    $fms_reports = new FMSReports($link1);
    $report_data = $fms_reports->showReports($tablename, $date);

    if(!empty($report_data) && $report_data !== 2){
        $flag = true;
        $keys = array_keys($report_data[0]);
    }
}

function getFilteredKeys($keys){
    $remove = ['id', 'created_date', 'update_date', 'updated_by', 'updated_ip'];

    return array_values(array_filter($keys, function($col) use ($remove) {
        return !in_array($col, $remove);
    }));
}

function printTableKeys($keys){
    $filteredKeys = getFilteredKeys($keys);

    $row = '<tr>';
    $row .= '<th>#</th>';

    foreach($filteredKeys as $key){
        $label = ucwords(str_replace("_", " ", $key));
        $row .= '<th>' . $label . '</th>';
    }

    $row .= "</tr>";

    return $row;
}
?>

<table width="100%" border="1" cellpadding="5" cellspacing="0">

    <thead style="background-color: green;color: white">
    <?= !empty($keys) ? printTableKeys($keys) : '' ?>
    </thead>

    <tbody>
    <?php
    if(!$flag){
        echo "<tr><td colspan='100%' style='text-align:center;'>No Data Found</td></tr>";
    }else{

        $filteredKeys = getFilteredKeys($keys);
        $sr = 1;

        foreach($report_data as $row){
            echo "<tr>";
            echo "<td>".$sr++."</td>";

            foreach($filteredKeys as $col){
                echo "<td>".(isset($row[$col]) ? $row[$col] : '')."</td>";
            }

            echo "</tr>";
        }
    }
    ?>
    </tbody>

</table>

<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=fms_report_".date('Y-m-d').".xls");
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1')

?>
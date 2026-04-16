<?php
require_once("../includes/config.php");
$column = [];
if (isset($_GET['tabname'], $_GET['formid'])) {

    $tablename = trim($_GET['tabname']);
    $formid = $_GET['formid'];

    if (!is_numeric($formid) || $formid <= 0) {
        die("Invalid form ID.");
    }
    $formid = (int)$formid;

    if ($tablename === '') {die("Invalid table name.");}

    // Escape just in case
    $tablename = mysqli_real_escape_string($link1, $tablename);

    $query = "SELECT parameter_name FROM form_master WHERE id = '$formid'";
    $result = mysqli_query($link1, $query);
    if ($result === false) {
        error_log("Query Failed: " . mysqli_error($link1));
        die("Database error.");
    }

    while ($row = mysqli_fetch_assoc($result)) {
        if (isset($row['parameter_name'])) {
            $column[] = json_decode($row['parameter_name']);
        }
    }

    mysqli_free_result($result);

} else {
    die("Missing required parameters.");
}

function dynamicColumn($column){

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
    <?= dynamicColumn($column[0])?>
    </tr>
</table>

<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=fms_report_".date('Y-m-d').".xls");
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');

?>

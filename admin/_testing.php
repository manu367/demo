<?php
require_once("../includes/config.php");
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
function getAllState($link1){
    $result = mysqli_query($link1, 'SELECT * FROM state_master ORDER BY state');
    $col = [];
    while($row = mysqli_fetch_assoc($result)){
        $col[] = $row['state']."(".$row['stateid'].")";
    }
    return $col;
}
function getAllCity($link1){
    $result = mysqli_query($link1, 'SELECT * FROM `city_master` ORDER BY state');
    $col = [];
    while($row = mysqli_fetch_assoc($result)){
        $col[] = $row['state']."-".$row['city']."(".$row['cityid'].")";
    }
    return $col;
}
function getAllData($link1,$tablename,$key_id,$key_value){
    $sql="select $key_id,$key_value from $tablename";
    $result=mysqli_query($link1,$sql);
    if(!$result || mysqli_num_rows($result)<=0) return [];
    $data=array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data = $row;
    }
    return $data;
}
// -------------------- DATA --------------------
$states = getAllState($link1);
$cities = getAllCity($link1);
// -------------------- EXCEL --------------------
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
// Headers
$sheet->setCellValue('A1', 'Name');
$sheet->setCellValue('B1', 'State');
$sheet->setCellValue('C1', 'City');
// -------------------- HIDDEN SHEET --------------------
$hiddenSheet = $spreadsheet->createSheet();
$hiddenSheet->setTitle('Hidden');
// Fill STATES in column A
$row = 1;
foreach ($states as $state) {
    $hiddenSheet->setCellValue("A$row", $state);
    $row++;
}
// Fill CITIES in column B
$row = 1;
foreach ($cities as $city) {
    $hiddenSheet->setCellValue("B$row", $city);
    $row++;
}
// Hide sheet
$hiddenSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
// -------------------- STATE DROPDOWN --------------------
$stateValidation = $sheet->getCell('B2')->getDataValidation();
$stateValidation->setType(DataValidation::TYPE_LIST);
$stateValidation->setErrorStyle(DataValidation::STYLE_STOP);
$stateValidation->setAllowBlank(true);
$stateValidation->setShowDropDown(true);
$stateValidation->setFormula1('Hidden!$A$1:$A$' . count($states));
// Apply to rows
for ($i = 2; $i <= 100; $i++) {
    $sheet->getCell("B$i")->setDataValidation(clone $stateValidation);
}
// -------------------- CITY DROPDOWN --------------------
$cityValidation = $sheet->getCell('C2')->getDataValidation();
$cityValidation->setType(DataValidation::TYPE_LIST);
$cityValidation->setErrorStyle(DataValidation::STYLE_STOP);
$cityValidation->setAllowBlank(true);
$cityValidation->setShowDropDown(true);
$cityValidation->setAllowBlank(true);
$cityValidation->setFormula1('Hidden!$B$1:$B$' . count($cities));
for ($i = 2; $i <= 100; $i++) {
    $sheet->getCell("C$i")->setDataValidation(clone $cityValidation);
}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
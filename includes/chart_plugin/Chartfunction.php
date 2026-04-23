<?php
global $link1;

function dynamicBinding($functionName, ...$params) {
    $functionName=strtolower($functionName);
    if (function_exists($functionName)) {
        return call_user_func_array($functionName, $params);
    } else {
        throw new Exception("Function '{$functionName}' does not exist");
    }
}
function count_Chart($fms_id){
    return [
        ["name" => "Count Data 1", "data" => [10, 20, 30, 40]],
        ["name" => "Count Data 2", "data" => [50, 60, 30, 90]],
        ["name" => "Count Data 3", "data" => [130, 220, 130, 80]]
    ];
}

function sum_Chart($fms_id){
    var_dump('sum funntion is running for fms_id='.$fms_id);
    return [
        ["name" => "Sum Data", "data" => [10, 20, 30, 40]]
    ];
}
function average_Chart($fms_id){
    var_dump('average funntion is running for fms_id='.$fms_id);
    return [
        ["name" => "Average Data", "data" => [10, 20, 5, 20,2,0,100,99,50,90,200]]
    ];
}

function week_Chart($fms_id){
    var_dump('weekly funntion is running for fms_id='.$fms_id);
    return [
        ["name" => "Monday",    "data" => [10, 20, 30, 40]],
        ["name" => "Tuesday",   "data" => [50, 60, 70, 80]],
        ["name" => "Wednesday", "data" => [90, 100, 110, 120]],
        ["name" => "Thursday",  "data" => [130, 140, 150, 160]],
        ["name" => "Friday",    "data" => [170, 180, 190, 200]],
        ["name" => "Saturday",  "data" => [210, 220, 230, 240]],
        ["name" => "Sunday",    "data" => [250, 260, 270, 280]],
    ];
}
function month_Chart($fms_id){
    var_dump('month funntion is running for fms_id='.$fms_id);
    return [
        ["name" => "Jan",   "data" => [10,  20,  30,  40]],
        ["name" => "Feb",   "data" => [50,  60,  70,  80]],
        ["name" => "March", "data" => [90,  100, 110, 120]],
        ["name" => "April", "data" => [130, 140, 150, 160]],
        ["name" => "May",   "data" => [170, 180, 190, 200]],
        ["name" => "Jun",   "data" => [210, 220, 230, 240]],
        ["name" => "July",  "data" => [250, 260, 270, 280]],
        ["name" => "Aug",   "data" => [290, 300, 310, 320]],
        ["name" => "Sep",   "data" => [330, 340, 350, 360]],
        ["name" => "Oct",   "data" => [370, 380, 390, 400]],
        ["name" => "Nov",   "data" => [410, 420, 430, 440]],
        ["name" => "Dec",   "data" => [450, 460, 470, 480]],
    ];
}
function yearly_Chart($fms_id){
    var_dump('year  funntion is running for fms_id='.$fms_id);
    return [
        ["name" => "2018", "data" => [10, 20, 30, 40]],
        ["name" => "2019", "data" => [50, 60, 70, 80]],
        ["name" => "2020", "data" => [90, 100, 110, 120]],
        ["name" => "2021", "data" => [130, 140, 150, 160]],
        ["name" => "2022", "data" => [170, 180, 190, 200]],
        ["name" => "2023", "data" => [210, 220, 230, 240]],
        ["name" => "2024", "data" => [250, 260, 270, 280]],
        ["name" => "2025", "data" => [290, 300, 310, 320]],
        ["name" => "2026", "data" => [330, 340, 350, 360]],
    ];
}
function ratio_Chart($fms_id){
    var_dump('ratio function is funntion is running for fms_id='.$fms_id);
    return [
        [
            "name" => "Rtio Data",
            "data" => [10, 20, 30, 40] // yaha DB se real data lao
        ]
    ];
}


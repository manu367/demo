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
        [
            "name" => "Count Data",
            "data" => [10, 20, 30, 40] // yaha DB se real data lao
        ]
    ];
}

function sum_Chart($fms_id){
    var_dump('sum funntion is running for fms_id='.$fms_id);
    return [
        [
            "name" => "Sum Data",
            "data" => [10, 20, 30, 40] // yaha DB se real data lao
        ]
    ];
}
function average_Chart($fms_id){
    var_dump('average funntion is running for fms_id='.$fms_id);
    return [
        [
            "name" => "Average Data",
            "data" => [10, 20, 30, 40] // yaha DB se real data lao
        ]
    ];
}

function week_Chart($fms_id){
    var_dump('weekly funntion is running for fms_id='.$fms_id);
    return [
        [
            "name" => "Week Data",
            "data" => [10, 20, 30, 40] // yaha DB se real data lao
        ]
    ];
}
function month_Chart($fms_id){
    var_dump('month funntion is running for fms_id='.$fms_id);
    return [
        [
            "name" => "month Data",
            "data" => [10, 20, 30, 40] // yaha DB se real data lao
        ]
    ];
}
function yearly_Chart($fms_id){
    var_dump('year  funntion is running for fms_id='.$fms_id);
    return [
        [
            "name" => "Year Data",
            "data" => [10, 20, 30, 40] // yaha DB se real data lao
        ]
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


<?php
require_once ("dropDownOperation.php");
require_once ("SaveChartsData.php");
function isparamValidation($data = [], $isValueCheck = false) {
    foreach ($data as $key => $value) {
        if (!isset($value)) {
            return false;
        }
        // optional value check
        if ($isValueCheck && empty($value)) {
            return false;
        }
    }
    return true;
}
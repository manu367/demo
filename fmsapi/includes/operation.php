<?php
function BasicValidation($link1,$fms_id,$form_id,$fms_name,$form_name){
    $share_data=[];
    $tabledata=loadFMsColumnTableName($link1,$fms_id);
    $from_data=loadFormId($link1,$form_id);
    if(!$from_data){
        SendResponse::sendResponseData(false,"Incorrect Form ID");
    }
    $share_data=[
        "id"=>$from_data['id'],
        "fms_id"=>$from_data['fms_id'],
        "form_name"=>$from_data['form_name'],
        "parameter_name"=>json_decode($from_data['parameter_name']),
        "display_name"=>json_decode($from_data['display_name']),
        "type"=>json_decode($from_data['type']),
        "length"=>json_decode($from_data['length']),
        "param_require"=>json_decode($from_data['param_require']),
    ];
    if(!$tabledata){
        SendResponse::sendResponseData(false,"Incorrect FMS ID");
    }
    if($tabledata['fmsname']!==$fms_name){
        SendResponse::sendResponseData(false,"Incorrect FMS Name");
    }
    if($from_data['form_name']!==$form_name){
        SendResponse::sendResponseData(false,"Incorrect Form Name");
    }
    return $share_data;
}

function storeDataUnit($share_data){
    $share = [];
    if (!empty($share_data['parameter_name'])) {
        for ($i = 0; $i < count($share_data['parameter_name']); $i++) {
            $share[] = new FormUnits(
                $share_data['parameter_name'][$i],
                '',
                $share_data['type'][$i] ?? null,
                $share_data['param_require'][$i] ?? null,
                $share_data['length'][$i] ?? null
            );
        }
    }
    return $share;
}

function loadFMsColumnTableName($link,$fmsid){
    $column=null;
    $result=mysqli_query ($link,"SELECT * FROM fms_master WHERE id=$fmsid");
    if($result){
        while ($row=mysqli_fetch_assoc($result)){
            $column=$row;
        }
    }
    return $column;
}

function loadFormId($link,$formid){
    $result=mysqli_query ($link,"SELECT * FROM `form_master` WHERE id='$formid'");
    if($result){
        while ($row=mysqli_fetch_assoc($result)){
            return $row;
        }
    }
    return null;
}

function loadTableColumn($link, $tablename) {
    $result = mysqli_query($link, "SHOW COLUMNS FROM `$tablename`");
    if (!$result) {
        return null;
    }
    $columns = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[] = $row['Field'];
    }
    return $columns;
}

function removeColumn($columns = [], $remove = []){
    return array_values(array_filter($columns, function ($col) use ($remove) {
        return !in_array($col, $remove);
    }));
}

function addFormData($tablename,$formunit=[]){
    $sql="INSERT INTO $tablename ";
    for($i=0;$i<count($formunit);$i++){

    }
}

function validateColumn($jsonColumn, $dbColumn) {
    $errors = [];

    $extraColumns = array_diff($jsonColumn, $dbColumn);

    if (!empty($extraColumns)) {
        $errors['extra_in_json'] = array_values($extraColumns);
    }

    // DB me jo hai par JSON me nahi aaya
    $missingColumns = array_diff($dbColumn, $jsonColumn);
    if (!empty($missingColumns)) {
        $errors['missing_in_json'] = array_values($missingColumns);
    }

    // Final response
    if (!empty($errors)) {
        return [
            "status" => false,
            "error_data" => $errors
        ];
    }

    return [
        "status" => true,
        "data" => $dbColumn
    ];
}


function saveData($link1,$body,$share_data,$formid){

    $fms_id = loadFormId($link1,$formid);
    $tablename = loadFMsColumnTableName($link1,$fms_id['fms_id']);
    $tablename = $tablename['table_name'];

    $column_type_db = loadType($link1);

    $typeMap = [];
    foreach ($column_type_db as $t){
        $typeMap[$t['id']] = $t['type'];
    }

    for($i=0;$i<count($share_data);$i++){

        $formunit = $share_data[$i];

        $value = isset($body[$formunit->parameter]) ? $body[$formunit->parameter] : null;
        $formunit->value = $value;


        if($formunit->require == 1){
            if($value === null || $value === ''){
                SendResponse::sendResponseData(false, $formunit->parameter . " is required");
            }
        }


        if(!empty($formunit->length) && $value !== null){
            if(strlen($value) > $formunit->length){
                SendResponse::sendResponseData(false, $formunit->parameter . " length exceeded");
            }
        }


        if(!empty($formunit->type)){
            if(!isset($typeMap[$formunit->type])){
                SendResponse::sendResponseData(false, "Invalid type mapping for ".$formunit->parameter);
            }

            $expectedType = $typeMap[$formunit->type];

            if(!validateType($value, $expectedType)){
                SendResponse::sendResponseData(false, $formunit->parameter . " type mismatch");
            }
        }
    }

    $query = createSqlQuery($link1,$tablename,$share_data);
    return insertData($link1,$query);
}

function createSqlQuery($link1, $table, $data){
    $columns = [];
    $values = [];

    foreach ($data as $item) {
        $columns[] = "`" . $item->parameter . "`";
        $val = mysqli_real_escape_string($link1, $item->value);
        $values[] = "'" . $val . "'";
    }

    $columns_str = implode(",", $columns);
    $values_str = implode(",", $values);

    $sql = "INSERT INTO `$table` ($columns_str) VALUES ($values_str)";
    return $sql;
}
function insertData($link1,$query){
    $result=mysqli_query($link1,$query);
    if(!$result){
        SendResponse::sendResponseData(true,"Insert Error");
    }else{
        return true;
    }
}


function loadType($link){
    $type=[];
    $result=mysqli_query($link,"SELECT * FROM `parameter_type` WHERE status=1");
    if($result){
        while ($row=mysqli_fetch_assoc($result)){
            $type[]=['id'=>$row['pt_id'],'type'=>$row['type']];
        }
    }
    return $type;
}


function validateType($value, $type){
    switch(strtolower($type)){
        case 'int':
        case 'integer':
            return filter_var($value, FILTER_VALIDATE_INT) !== false;
        case 'float':
        case 'double':
            return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
        case 'email':
            return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
        case 'string':
            return is_string($value);
        case 'date':
            return strtotime($value) !== false;
        default:
            return true;
    }
}
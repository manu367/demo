<?php

function loadform_1($link1,$id){
    $data=[];
    $result=mysqli_query($link1,"SELECT * FROM `form_master` where id = $id LIMIT  1");
    if($result){
        while ($row=mysqli_fetch_assoc($result)){
            $data[]=$row;
        }
    }
    return $data[0];
}

class FormViewSHow implements JsonSerializable {
    public $formid,$formName,$fms_id,$form_seq;
    public $form_data=[];
    public function __construct($formid, $formName, $fms_id, $form_seq, $form_data)
    {
        $this->formid = $formid;
        $this->formName = $formName;
        $this->fms_id = $fms_id;
        $this->form_seq = $form_seq;
        $this->form_data = $form_data;
    }
    public function jsonSerialize(){return get_object_vars($this);}
}
class FormBaiscData{
    public $paramName,$displayName,$type,$length,$param_require,$dropdown;
    public function __construct($paramName, $displayName, $type, $length, $param_require, $dropdown){
        $this->paramName = $paramName;
        $this->displayName = $displayName;
        $this->type = $type;
        $this->length = $length;
        $this->param_require = $param_require;
        $this->dropdown = $dropdown;
    }

}

function reqiuireOrNot($req){
    $reqDiv=$req==='0'?'':'<span class="red_small" style="color: red">*</span>';
    $reqRequire=$req==='0'?'':'required';
    return [$reqDiv,$reqRequire];
}

function createForm($link1,$data){
    $html="";
    for($i=0;$i<count($data->form_data);$i++){
       if($data->form_data[$i]->type==='8'){
           $html.=createFullSelectBox($link1,
               $data->form_data[$i]->dropdown,
               $data->form_data[$i]->paramName,
               reqiuireOrNot($data->form_data[$i]->param_require));
       }
       else{
           $html .= inputtype(
               $data->form_data[$i]->paramName,
               $data->form_data[$i]->displayName,
               $data->form_data[$i]->type,
               $data->form_data[$i]->length,
               reqiuireOrNot($data->form_data[$i]->param_require)
           );
        }
    }
    return $html;
}

function inputtype($paraname,$displayname,$inputType,$length,$req=[]){

    $currentDate = date('Y-m-d');
    $currentDateTime = date('Y-m-d\TH:i');

    $extraAttr = "";

    switch ($inputType) {
        case 1:
            $inputType = "text";
            $extraAttr = "maxlength='{$length}'";
            break;

        case 2:
            $inputType = "email";
            $extraAttr = "maxlength='{$length}'";
            break;

        case 3:
            $inputType = "number";
            $extraAttr = "maxlength='{$length}' pattern='[0-9]{1,{$length}}' 
            oninput=\"this.value=this.value.replace(/[^0-9]/g,'').slice(0,{$length})\"";
            break;

        case 4:
            $inputType = "password";
            $extraAttr = "maxlength='{$length}'";
            break;

        case 5:
            $inputType = "date";
            $extraAttr = "max='{$currentDate}'"; // 👈 future block
            break;

        case 6:
            $inputType = "datetime-local";
            $extraAttr = "max='{$currentDateTime}'"; // 👈 future block
            break;
        case 7:
            $inputType = "file";
            $extraAttr = "accept='.pdf,.png,.jpg,.jpeg'";
            break;
        default:
            break;
    }
    return "<div>
            <label class='label'>$displayname $req[0]
            </label>
            <input name='{$paraname}' type='{$inputType}' class='input' {$extraAttr} {$req[1]}>
        </div>";
}

function createFullSelectBox($link1, $id,$label,$require=[]){
    $html = "<div>";
    $html .="<label class='label'>$label</label>";
    $html.=getSelectBoxbasedOnTable_1($link1, $id,$label);
    $html .= "</div>";
    return $html;
}

function getSelectBoxbasedOnTable_1($link1, $id,$label){
    $sql = "SELECT master_table, key_id, key_value
            FROM dropdown_master
            WHERE id = '$id' AND status = 1";

    $result = mysqli_query($link1, $sql);

    if(!$result || mysqli_num_rows($result) == 0){
        return "<select class='input'><option>No Data</option></select>";
    }

    $row = mysqli_fetch_assoc($result);

    return createSelectBox_1($link1, $row['master_table'], $row['key_id'], $row['key_value'],$label);
}
function createSelectBox_1($link1, $tablename, $keyid, $key_value,$label){

    if(empty($tablename) || empty($keyid) || empty($key_value)){
        return "<select class='input'><option>Invalid config</option></select>";
    }

    $sql = "SELECT $keyid, $key_value FROM $tablename";

    $result = mysqli_query($link1, $sql);

    if(!$result){
        return "<select class='input'><option>Error loading</option></select>";
    }

    $html ="<input type='hidden' name='table_name_hidden' value='".$tablename."'>";
    $html .= "<select name='".$label."' class='input'>";
    $html .= "<option value=''>--Select--</option>";

    while($row = mysqli_fetch_assoc($result)){
        $html .= "<option value='".$row[$keyid]."'>".$row[$key_value]."</option>";
    }

    $html .= "</select>";
    return $html;
}
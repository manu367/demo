<?php
function getAllDropDownMenus($link1){
    $sql="SELECT * FROM `dropdown_master` where  status=1";
    $result=mysqli_query($link1,$sql);
    $num=mysqli_num_rows($result);
    if(!$result){
        return [];
    }
    if($num<=0){
    return [];
    }
    $data=[];
    while ($row=mysqli_fetch_assoc($result)){
        $data[]=$row;
    }
    return $data;
}

function showDropDown_master($link, $id = 0){
    $data = getAllDropDownMenus($link);

    if(empty($data)){
        return "<select name='drop_down[]'><option>No Data Found</option></select>";
    }
    $html = "<select name='drop_down[]' class='form-control' name='drop_down_master'>";
    $html .= "<option value=''>--Select Master--</option>";

    foreach($data as $row){
        $selected = ($id == $row['id']) ? "selected" : "";
        $html .= "<option value='".$row['id']."' $selected>".$row['master_name']."</option>";
    }
    $html .= "</select>";
    return $html;
}
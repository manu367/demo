<?php
require_once("includes/secuity.php");
class FormMasterModal implements JsonSerializable{
    public $form_id,$form_name,$status,$frm_seq;
    public $form_basic_detail;

    /**
     * @param $form_id
     * @param $form_name
     * @param $status
     * @param $frm_seq
     * @param $form_basic_detail
     */
    public function __construct($form_id, $form_name, $status, $frm_seq, $form_basic_detail)
    {
        $this->form_id = $form_id;
        $this->form_name = $form_name;
        $this->status = $status;
        $this->frm_seq = $frm_seq;
        $this->form_basic_detail = $form_basic_detail;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}

class FormBasicDetails implements JsonSerializable{
    private $paramter_name,
        $display_name,
        $length,
        $param_require,
        $type,
        $drop_down;

    public function __construct($paramter_name, $display_name, $length, $param_require, $type, $drop_down)
    {
        $this->paramter_name = $paramter_name;
        $this->display_name = $display_name;
        $this->length = $length;
        $this->param_require = $param_require;

        $this->type = new ParamterTypeModel('', $type, '1');

        if($drop_down !== '0'){
            $dropdownObj = new GetAllDataFromTable($type, $drop_down);
            $this->drop_down = $dropdownObj->dropdownType($drop_down); // ✅ FIXED
        } else {
            $this->drop_down = [];
        }
    }

    public function jsonSerialize()
    {
        return [
            "paramter_name" => $this->paramter_name,
            "display_name" => $this->display_name,
            "length" => $this->length,
            "param_require" => $this->param_require,
            "type" => $this->type,
            "drop_down" => $this->drop_down
        ];
    }
}
class ParamterTypeModel implements JsonSerializable{
    private $id,$type,$status;

    /**
     * @param $id
     * @param $type
     * @param $status
     */
    public function __construct($id, $type, $status)
    {
        $this->id = $id;
        $this->type = $this->getAllfromTable($type);
        $this->status = $status;
    }

    public function getTypeData($type){
    }
    public function getAllfromTable($type){
        global $conn;
        $sql="SELECT * FROM `parameter_type` where pt_id='$type'";
        $result = mysqli_query($conn->getConnection(), $sql);
        $row=mysqli_fetch_assoc($result);
        return ['param_type'=>$type,'parameter_type'=>$row['type']];

    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}

class GetAllDataFromTable implements JsonSerializable{
    private $dropdown_type;
    private $table_id;

   public function __construct($dropdown_type, $table_id){
        $this->dropdown_type = $dropdown_type;
        $this->table_id = $table_id;

    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function dropdownType($tableid){
        global $conn;

        $sql = "SELECT * FROM `dropdown_master` WHERE id = '$tableid'";
        $result = mysqli_query($conn->getConnection(), $sql);

        if(!$result){
            return [];
        }

        $row = mysqli_fetch_assoc($result);

        if($row === null){
            return [];
        }

        $key_id = $row['key_id'];
        $key_value = $row['key_value'];
        $table = $row['master_table'];

        // basic validation (IMPORTANT)
        if(!preg_match('/^[a-zA-Z0-9_]+$/', $table)){
            return [];
        }

        $sql2 = "SELECT $key_id, $key_value FROM $table";
        $result2 = mysqli_query($conn->getConnection(), $sql2);

        if(!$result2){
            return [];
        }

        $data = [];
        while($r = mysqli_fetch_assoc($result2)){
            $data[] = $r;
        }

        return [
            "table" => $table,
            "data" => $data
        ];
    }

}
<?php
class FormMasterModal{
    public $id,$create_date,$update_date,$created_by,$updated_by,$updated_ip;
    private $formid ,$formName,$fms_id;
    private $formBasicDetails=array();
    public static function Builder(){
        return new FormMasterModal();
    }
    public function setFormid($formid){
        $this->formid = $formid;
        return $this;
    }
    public function setFormName($formname){
        $this->formName = $formname;
        return $this;
    }
    public function setFmsID($fmsid){
        $this->fms_id = $fmsid;
        return $this;
    }
    public function setFunctionBasicDEtails(FormBasicDetails $formBasicDetails){
        $this->formBasicDetails[]=$formBasicDetails;
    }
}
class FormBasicDetails{
    public $parameter_name,$display_name,$type,$length;
    public function Builder(){
        return new FormBasicDetails();
    }
    public function setParameterName($parameter_name=''){
        $this->parameter_name = $parameter_name;
        return $this;
    }
    public function setDisplayName($display_name=''){
        $this->display_name = $display_name;
        return $this;
    }
    public function setType($type=''){
        $this->type = $type;
        return $this;
    }
    public function setLength($length){
        $this->length = $length;
        return $this;
    }
    public function validate(){}
    public function build(){
        return $this;
    }
}

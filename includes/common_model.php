<?php
class FormUnits{
    public $parameter,$value,$type,$require,$length;
    public function __construct($parameter,$value,$type,$require,$length) {
        $this->parameter = $parameter;
        $this->value = $value;
        $this->type = $type;
        $this->require = $require;
        $this->length = $length;
    }
    public function getparameter(){
        return $this->parameter;
    }
    public function getvalue(){
        return $this->value;
    }
    public function gettype(){
        return $this->type;
    }
    public function getrequire(){
        return $this->require;
    }
    public function getlength(){
        return $this->length;
    }
}
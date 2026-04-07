<?php
class GlobalValue {
    private $from_id;
    private $form_name;
    private $fms_id;
    private $fms_name;
    private $accesskey;

    public function __construct($builder) {
        $this->from_id   = $builder->from_id;
        $this->form_name = $builder->form_name;
        $this->fms_id    = $builder->fms_id;
        $this->fms_name  = $builder->fms_name;
        $this->accesskey = $builder->accesskey;
    }

    public function toArray() {
        return [
            "from_id"   => $this->from_id,
            "form_name" => $this->form_name,
            "fms_id"    => $this->fms_id,
            "fms_name"  => $this->fms_name,
            "accesskey" => $this->accesskey,
        ];
    }
}
class GlobalValueBuilder {
    public $from_id;
    public $form_name;
    public $fms_id;
    public $fms_name;
    public $accesskey;

    public function setFromId($from_id) {
        $this->from_id = $from_id;
        return $this;
    }

    public function setFormName($form_name) {
        $this->form_name = $form_name;
        return $this;
    }

    public function setFmsId($fms_id) {
        $this->fms_id = $fms_id;
        return $this;
    }

    public function setFmsName($fms_name) {
        $this->fms_name = $fms_name;
        return $this;
    }

    public function setAccessKey($accesskey) {
        $this->accesskey = $accesskey;
        return $this;
    }

    public function build() {
        return new GlobalValue($this);
    }
}

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

class SendResponse {
    public static function sendResponseData($status = false, $data = []) {
        header('Content-Type: application/json');
        echo json_encode([
            "status" => $status,
            "data" => $data
        ], JSON_PRETTY_PRINT);
        exit();
    }
}
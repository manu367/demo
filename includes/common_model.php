<?php
class FormSaveModel{
    public $parameter,$value,$type,$require,$length,$old_column;
    public function __construct($parameter,$value,$type,$require,$length,$old_column) {
        $this->parameter = $parameter;
        $this->value = $value;
        $this->type = $type;
        $this->require = $require;
        $this->length = $length;
        $this->old_column = $old_column;
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

class TabPermission {
    public $tabid, $add, $edit, $view, $cancel, $print, $report, $approval, $price_display;

    public function __construct($tabid, $add, $edit, $view, $cancel, $print, $report, $approval, $price_display){
        $this->tabid = $tabid;
        $this->add = $add;
        $this->edit = $edit;
        $this->view = $view;
        $this->cancel = $cancel;
        $this->print = $print;
        $this->report = $report;
        $this->approval = $approval;
        $this->price_display = $price_display;
    }

    public function validate(){
        $this->add = $this->add ? 'Y' : 'N';
        $this->edit = $this->edit ? 'Y' : 'N';
        $this->view = $this->view ? 'Y' : 'N';
        $this->cancel = $this->cancel ? 'Y' : 'N';
        $this->print = $this->print ? 'Y' : 'N';
        $this->report = $this->report ? 'Y' : 'N';
        $this->approval = $this->approval ? 'Y' : 'N';
        $this->price_display = $this->price_display ? 'Y' : 'N';
    }
}
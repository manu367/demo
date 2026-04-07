<?php

class FMSDetailsModel{
    public $fmsid,$fmsname,$fms_details,$steps,$total_form,$status,$table_name;
    public $form_master;
    public function __construct($fmsid, $fmsname, $fms_details, $steps, $total_form, $status, $table_name)
    {
        $this->fmsid = $fmsid;
        $this->fmsname = $fmsname;
        $this->fms_details = $fms_details;
        $this->steps = $steps;
        $this->total_form = $total_form;
        $this->status = $status;
        $this->table_name = $table_name;
    }
    public function getFormMaster()
    {
        return $this->form_master;
    }

    public function setFormMaster($form_master): void
    {
        $this->form_master = $form_master;
    }

}
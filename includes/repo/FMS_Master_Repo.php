<?php
class FMS_Master_Repo{
    private $link1;
    public function __construct($link1){
        $this->link1=$link1;
    }
    public function getAllFMS(){}
    public function getFMSById($fmsid){

    }
    public function getFMSByName($fmsname){}
    public function saveFMS($fms){}
    public function deleteFMS($fms){}
    public function  updateFMS($fmsid,$fms){}
}

<?php
class FMS_Master_Service{
    private $conn,$repo;
    public function __construct($link1){
        $this->conn=$link1;
        $this->repo=new FMS_Master_Repo($this->conn);
    }
    public function getAllFMS(){}
    public function getFMSById($fmsid=''){
        if($this->conn===null){
            throw new Exception("DB unable to connect");
        }
        if($fmsid===''){
            throw new Exception("FMS id can't be empty");
        }
        return $this->repo->getFMSById($fmsid);
    }
    public function getFMSByName($fmsname){}
    public function getFmsbyCategory(){}
    public function saveFMS($fms){}
    public function deleteFMS($fms){}
    public function  updateFMS($fmsid,$fms){}

}
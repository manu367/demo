<?php
class ChartService{
    private $connection;
    private $repo;
    public function __construct($connection){
        $this->connection = $connection;
        $this->repo=new ChartRepo($connection);
    }

    public function saveChartService($data=[]){
        mysqli_autocommit($this->connection, FALSE);
        $res=$this->repo->saveChartRepo($data);
        if($res){
            mysqli_commit($this->connection);
            return true;
        }else{
            mysqli_rollback($this->connection);
            return false;
        }

    }
    public function getChartServiceByid($id){
        return $this->repo->getChartRepoById($id);
    }
    public function updateChartData($id,$data=[]){
        $res=$this->repo->updateChartRepo($id,$data);
        if($res){
            return true;
        }else{
            return false;
        }
    }
}
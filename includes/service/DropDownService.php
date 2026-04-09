<?php
class DropDownService{
    private $connection;
    private $repo;
    public function __construct($connection){
        $this->connection = $connection;
        $this->repo=new DropDownRepo($this->connection);
    }

    public function saveDropDownData($data=[]){
        mysqli_autocommit($this->connection, FALSE);

        $res=$this->repo->saveDropdonw($data);

        if($res){
            mysqli_commit($this->connection);
            return true;
        }else{
            mysqli_rollback($this->connection);
            return false;
        }

    }
    public function getDropDownDataByid($id){
        return $this->repo->getDropDownDataByid($id);
    }
    public function updateDropDownData($data=[]){
        $res=$this->repo->updateDropdown($data['dropdown_id'],$data);
        if($res){
            return true;
        }else{
            return false;
        }
    }
}
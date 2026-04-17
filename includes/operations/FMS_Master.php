<?php

class FMS_Master_Operations{
    private $link,$service;
    public function __construct($link){
        $this->link=$link;
        $this->service=new FMS_Master_Service($link);
    }
    public function addFMS_Master($data){}
    public function updateFMS_Master($data){}
    public function clone_FmsMaster($data){}
    public function spaceRemover($name){}
}


class Form_Master_Operations{
    public $pid,$hid,$connection;

    public function getPid()
    {
        return $this->pid;
    }

    public function setPid($pid): void
    {
        $this->pid = $pid;
    }
    public function getHid()
    {
        return $this->hid;
    }

    public function setHid($hid): void
    {
        $this->hid = $hid;
    }

    public function getConnection()
    {
        return $this->connection;
    }
    public function setConnection($connection): void
    {
        $this->connection = $connection;
    }

    public function add_FormMaster(){}
    public function update_formMaster(){}
    public function cloneFormMaster(){}
}
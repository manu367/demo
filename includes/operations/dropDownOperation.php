<?php
class DropDownMaster{
    private $connection;
    private $service;
    private $updateip,$updatedBy;
    public function __construct($connection){
        $this->connection = $connection;
        $this->service = new DropDownService($connection);
    }
    public function setupdateBy_and_IP($by,$ip){
        $this->updatedBy = $by;
        $this->updateip = $ip;
    }
    public function saveDropDownData($data){

        $dropdownname = trim($data['master_name'] ?? '');
        $master_table = trim($data['master_table'] ?? '');
        $table_keys   = trim($data['key_id'] ?? '');
        $table_value  = trim($data['key_value'] ?? '');

        if($table_keys === $table_value){
            throw new ValidationException("Table key and value cannot be same");
        }

        if($dropdownname === ''){
            throw new ValidationException("Dropdown name cannot be empty");
        }

        if(strlen($dropdownname) < 5){
            throw new ValidationException("Dropdown name must be at least 5 characters");
        }

        if($master_table === ''){
            throw new ValidationException("Master table is required");
        }

        if($table_keys === '' || $table_value === ''){
            throw new ValidationException("Key and Value are required");
        }
       return $this->service->saveDropDownData([
            "master_name"=>$dropdownname,
            "master_table"=>$master_table,
            "key_id"=>$table_keys,
            "key_value"=>$table_value,
            "updated_by"=>$this->updatedBy,
            "updated_ip"=>$this->updateip
        ]);
    }
    public function getDropDownData($id){
        if($id===''){
            throw new ValidationException("Id is required");
        }
        return $this->service->getDropDownDataByid($id);
    }
    public function updateDateDropDownData($data){
        $dropdownid=$data['dropdown_id']??'';
        $dropdownname = trim($data['master_name'] ?? '');
        $master_table = trim($data['master_table'] ?? '');
        $table_keys   = trim($data['key_id'] ?? '');
        $table_value  = trim($data['key_value'] ?? '');

        if($dropdownid === ''){
            throw new ValidationException("Dropdown Id cannot be empty");
        }

        if($table_keys === $table_value){
            throw new ValidationException("Table key and value cannot be same");
        }

        if($dropdownname === ''){
            throw new ValidationException("Dropdown name cannot be empty");
        }

        if(strlen($dropdownname) < 5){
            throw new ValidationException("Dropdown name must be at least 5 characters");
        }

        if($master_table === ''){
            throw new ValidationException("Master table is required");
        }

        if($table_keys === '' || $table_value === ''){
            throw new ValidationException("Key and Value are required");
        }

        return $this->service->updateDropDownData([
            "dropdown_id"=>$dropdownid,
            "master_name"=>$dropdownname,
            "master_table"=>$master_table,
            "key_id"=>$table_keys,
            "key_value"=>$table_value,
            "updated_by"=>$this->updatedBy,
            "updated_ip"=>$this->updateip
        ]);
    }
}
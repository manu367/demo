<?php
class DropDownRepo {

    private $connection;

    public function __construct($connection){
        $this->connection = $connection;
    }

    // INSERT
    public function saveDropdonw($data = []){

        $master_name  = mysqli_real_escape_string($this->connection, $data['master_name']);
        $master_table = mysqli_real_escape_string($this->connection, $data['master_table']);
        $key_id       = mysqli_real_escape_string($this->connection, $data['key_id']);
        $key_value    = mysqli_real_escape_string($this->connection, $data['key_value']);
        $status       = '1';
        $updated_by   = mysqli_real_escape_string($this->connection, $data['updated_by']);
        $updated_ip   = mysqli_real_escape_string($this->connection, $data['updated_ip']);

        $sql = "
            INSERT INTO dropdown_master 
            (master_name, master_table, key_id, key_value, status, updated_by, updated_ip, update_date)
            VALUES 
            ('$master_name', '$master_table', '$key_id', '$key_value', '$status', '$updated_by', '$updated_ip', NOW())
        ";
        return mysqli_query($this->connection, $sql) or false;
    }

    // UPDATE
    public function updateDropdown($id, $data){

        $id           = (int)$id;
        $master_name  = mysqli_real_escape_string($this->connection, $data['master_name']);
        $master_table = mysqli_real_escape_string($this->connection, $data['master_table']);
        $key_id       = mysqli_real_escape_string($this->connection, $data['key_id']);
        $key_value    = mysqli_real_escape_string($this->connection, $data['key_value']);
        $status       = (int)$data['status'];
        $updated_by   = mysqli_real_escape_string($this->connection, $data['updated_by']);
        $updated_ip   = mysqli_real_escape_string($this->connection, $data['updated_ip']);

        $sql = "
            UPDATE dropdown_master 
            SET 
                master_name = '$master_name',
                master_table = '$master_table',
                key_id = '$key_id',
                key_value = '$key_value',
                status = '1',
                updated_by = '$updated_by',
                updated_ip = '$updated_ip',
                update_date = NOW()
            WHERE id = $id
        ";
        return mysqli_query($this->connection, $sql) or false;
    }

    public function getAllDropDownState(){
        $sql = "SELECT * FROM dropdown_master WHERE status = 1 ORDER BY id DESC";
        $result = mysqli_query($this->connection, $sql);
        $data = [];
        if($result){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getDropDownDataById($id){
        $id = (int)$id;
        $sql = "SELECT * FROM dropdown_master WHERE id = $id LIMIT 1";
        $result = mysqli_query($this->connection, $sql);

        if($result && mysqli_num_rows($result) > 0){
            return mysqli_fetch_assoc($result);
        }
        return null;
    }
}
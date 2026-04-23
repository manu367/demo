<?php
class ChartRepo {

    private $connection;

    public function __construct($connection){
        $this->connection = $connection;
    }

    // INSERT
    public function saveChartRepo($data = []){

        $chart_type   = mysqli_real_escape_string($this->connection, $data['chart']);
        $chart_title  = mysqli_real_escape_string($this->connection, $data['charttitle']);
        $table_name   = mysqli_real_escape_string($this->connection, $data['table_name']);
        $x_axis       = mysqli_real_escape_string($this->connection, $data['parameter_value']);
        $y_axis       = mysqli_real_escape_string($this->connection, $data['parameter_value_2']);
        $operation    = mysqli_real_escape_string($this->connection, $data['operations']);
        $status       = mysqli_real_escape_string($this->connection, $data['status']);
        $created_at   = date('Y-m-d H:i:s');
        $updated_at   = date('Y-m-d H:i:s');
        $updated_by   = mysqli_real_escape_string($this->connection, $data['updated_by']);
        $updated_ip   = mysqli_real_escape_string($this->connection, $data['updated_ip']);

        $sql = "
        INSERT INTO dashboard_master (
            chart_type,chart_title,table_name,x_axis,y_axis,operation,status,created_at,updated_at,updated_by,updated_ip) VALUES (
            '$chart_type','$chart_title','$table_name','$x_axis','$y_axis','$operation',
            '$status','$created_at','$updated_at','$updated_by','$updated_ip')";
        return mysqli_query($this->connection, $sql) or false;
    }

    // UPDATE
    public function updateChartRepo($id, $data){

        $id = (int)$id;

        $chart_type   = mysqli_real_escape_string($this->connection, $data['chart']);
        $chart_title  = mysqli_real_escape_string($this->connection, $data['charttitle']);
        $table_name   = mysqli_real_escape_string($this->connection, $data['table_name']);
        $x_axis       = mysqli_real_escape_string($this->connection, $data['parameter_value']);
        $y_axis       = mysqli_real_escape_string($this->connection, $data['parameter_value_2']);
        $operation    = mysqli_real_escape_string($this->connection, $data['operations']);
        $status       = mysqli_real_escape_string($this->connection, $data['status']);
        $updated_by   = mysqli_real_escape_string($this->connection, $data['updated_by']);
        $updated_ip   = mysqli_real_escape_string($this->connection, $data['updated_ip']);
        $updated_at   = date('Y-m-d H:i:s');

        $sql = "
        UPDATE dashboard_master 
        SET 
            chart_type   = '$chart_type',
            chart_title  = '$chart_title',
            table_name   = '$table_name',
            x_axis       = '$x_axis',
            y_axis       = '$y_axis',
            operation    = '$operation',
            status       = '$status',
            updated_by   = '$updated_by',
            updated_ip   = '$updated_ip',
            updated_at   = '$updated_at'
        WHERE id = $id
    ";
        return mysqli_query($this->connection, $sql) or false;
    }

    public function getAllChartsRepo(){
        $sql = "SELECT * FROM `dashboard_master` WHERE status = 1 ORDER BY id DESC";
        $result = mysqli_query($this->connection, $sql);
        $data = [];
        if($result){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getChartRepoById($id){
        $id = (int)$id;
        $sql = "SELECT * FROM `dashboard_master` WHERE id = $id LIMIT 1";
        $result = mysqli_query($this->connection, $sql);

        if($result && mysqli_num_rows($result) > 0){
            return mysqli_fetch_assoc($result);
        }
        return null;
    }
}
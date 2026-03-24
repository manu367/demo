<?php

class FMsBasicOperation{
 public static function loadFMS($link,$fabid){
     $result=mysqli_query($link,"select * from fms_master where id='$fabid'");
     if($result){
         while ($row=mysqli_fetch_assoc($result)){
             return $row;
         }
     }
     return null;
 }
}


class FormModel{
    private $name, $displayname,$type,$length;
    public function __construct($name,$displayname,$type,$length){
        $this->name = $name;
        $this->displayname = $displayname;
        $this->type = $type;
        $this->length = $length;
    }
    public function getName(){
        return $this->name;
    }
    public function getDisplayname(){
        return $this->displayname;
    }
    public function getType(){
        return $this->type;
    }
    public function getLength(){
        return $this->length;
    }
}
/**
 *  FMS_Operation is class
 *  Whose maintain the Operation : Add, Update , RedirectOperation in the Page
 * <br/>
 *  addOperation
 *  updateOOperation
 */
class FMS_Operations{
    private $pid,$hid,$location;
    private $conn;
    function __construct($pid,$hid,$location,$conn){
        $this->pid = $pid;
        $this->hid = $hid;
        $this->location = $location;
        $this->conn = $conn;
    }
    public function updateOperation($data = [], $updateBy, $fms_id){
//        var_dump($data,$updateBy,$fms_id);exit();
        $fname       = $data['fmsname'];
        $details     = $data['details'];
        $steps       = (int)$data['steps'];
        $total_form  = (int)$data['total_form'];
        $updated_by  = $updateBy;
        $updated_ip  = $_SERVER['REMOTE_ADDR'];

        $sql = "UPDATE fms_master SET 
                fmsname     = '$fname',
                details     = '$details',
                steps       = $steps,
                total_form  = $total_form,
                updated_at  = CURRENT_TIMESTAMP,
                updated_by  = '$updated_by',
                updated_ip  = '$updated_ip'
            WHERE id = '$fms_id'";


        $rs=mysqli_query($this->conn, $sql);
        if(!rs){
            return ['status'=>false, "msg"=>'Some thins is wrong, Not Updated'];
        }
        return ['status'=>true, "msg"=>'Successfully Updated'];
    }
    public function addOperation($data = [], $updateBy,$tablname){
        $fname       = mysqli_real_escape_string($this->conn, $data['fmsname']);
        $details     = mysqli_real_escape_string($this->conn, $data['details']);
        $steps       = (int)$data['steps'];
        $total_form  = (int)$data['total_form'];
        $updated_by  = mysqli_real_escape_string($this->conn, $updateBy);
        $updated_ip  = $_SERVER['REMOTE_ADDR'];

        $sql = "INSERT INTO fms_master 
        (fmsname, details, steps, total_form, updated_by, updated_ip, created_at, updated_at, table_name)
        VALUES 
        ('$fname', '$details', $steps, $total_form, '$updated_by', '$updated_ip', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '$tablname')";
        if(!mysqli_query($this->conn, $sql)){
            return ['status'=>false, "msg"=>mysqli_error($this->conn)];
        }

        return ['status'=>true, "msg"=>'Successfully Added'];
    }
    public function redirect($type,$msg){
        header("location:$this->location?pid=$this->pid&hid=$this->hid&type=$type&msg=$msg");
        exit();
    }

    public function checkAlreadTableisExistorNot($value){

    }
    // default column
    /*
     *  id -primary key
     *  update_date
     * create_date
     * update_by
     * updated_ip
     */
    public function checkAlreadyExist($name)
    {
        $arr=[];
        try {
            $name = trim($name);
            $tableName = str_replace(' ', '_', $name);
            $tableName = preg_replace('/[^A-Za-z0-9_]/', '', $tableName);

            if (empty($tableName)) {
                throw new Exception("Invalid table name after sanitization");
            }
            $checkQuery = "SHOW TABLES LIKE '$tableName'";
            $result=mysqli_query($this->conn,$checkQuery);
            if(mysqli_num_rows($result)>0){
                $i=0;
                while($row=mysqli_fetch_array($result)){
                    $arr[]=$row[$i];
                    $i++;
                }
            }
            return $arr;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function createTable($name)
    {
        try {
            $tableName=$this->spaceRemover($name);
            $tableName='fms_'.$tableName;
            $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            update_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by varchar(10),
    updated_ip varchar(40)
        ) ENGINE=InnoDB";

            if (!mysqli_query($this->conn,$sql)) {
                throw new GlobalException("Error creating table: " . $this->conn->error);
            }

            return true;

        } catch (Exception $e) {
            throw new GlobalException($e->getMessage());
        }
        return false;
    }
    public function spaceRemover($name){
        $name = trim($name);
        if (empty($name)) {
            throw new GlobalException("Table name cannot be empty");
        }

        $tableName = str_replace(' ', '_', $name);
        $tableName = preg_replace('/[^A-Za-z0-9_]/', '', $tableName);

        if (empty($tableName)) {
            throw new GlobalException("Invalid table name after sanitization");
        }
        $tableName=strtolower($tableName);
        return $tableName;
    }
}

class FormOperations{
    private $pid,$hid,$location,$conn;
    public function __construct($pid,$hid,$location,$conn)
    {
        $this->pid = $pid;
        $this->hid = $hid;
        $this->location = $location;
        $this->conn = $conn;

    }
    public function spaceRemover($name){
        $name = trim($name);
        if (empty($name)) {
            throw new GlobalException("Table name cannot be empty");
        }

        $tableName = str_replace(' ', '_', $name);
        $tableName = preg_replace('/[^A-Za-z0-9_]/', '', $tableName);

        if (empty($tableName)) {
            throw new GlobalException("Invalid table name after sanitization");
        }
        $tableName=strtolower($tableName);
        return $tableName;
    }
    public function loadForm($fms_id,$form_id){}
    public function updateForm($id, $fms_id, $data, $updatedBy,$tablename="")
    {
        $updated_at = date('Y-m-d H:i:s');
        $updated_ip = $_SERVER['REMOTE_ADDR'];
        $formname     = $data['formname'];
        $paramername  =  $data['name'];
        $displayName  = $data['displayName'];
        $type         = $data['type'];
        $length       = $data['length'];
        $fms_id       = $fms_id;
        $updatedBy    = $updatedBy;
        $id           = (int)$id;

        $newcolumn=[
            "type"=>json_decode($type),
            "length"=>json_decode($length),
            "newcolumn"=>json_decode($paramername),
        ];
         $result=mysqli_query($this->conn,"DESCRIBE $tablename");
         $oldcolumn=[];
         if($result){
             while ($row=mysqli_fetch_array($result)){
                 $oldcolumn[]=$row['Field'];
             }
         }

        $remove = ['id', 'created_date', 'update_date', 'updated_by', 'updated_ip'];
        $oldcolumn = array_values(array_filter($oldcolumn, function($col) use ($remove) {
            return !in_array($col, $remove);
        }));


        $newcolumn_add=json_decode($paramername);

        // new column name
        $diff = array_values(array_diff($newcolumn_add, $oldcolumn));
        $this->addMoreParameteronUpdareTime($tablename,$diff);
        $val_res=$this->columnUpdate($tablename,$oldcolumn,$newcolumn);

        if($val_res){
            $query = "UPDATE form_master SET updated_date = '$updated_at',
                       updated_by = '$updatedBy',
                       updated_ip = '$updated_ip',
                       form_name = '$formname',
                       fms_id = '$fms_id',
                       parameter_name = '$paramername',
                       display_name = '$displayName',
                       type = '$type',
                       length = '$length'
                   WHERE id = $id";
            return mysqli_query($this->conn, $query);
        }
        return false;
    }

    public function addForm($fms_id, $data, $createdBy)
    {

        $fmsid=$fms_id;
        $formname     = $data['formname'];
        $paramername  = $data['name'];
        $displayName  = $data['displayName'];
        $type         = $data['type'];
        $length       = $data['length'];


        $created_at = date('Y-m-d H:i:s');
        $updated_at = $created_at;
        $updated_by = $createdBy;
        $updated_ip = $_SERVER['REMOTE_ADDR'];

        $query = "INSERT INTO form_master 
        (created_date, updated_date, created_by, updated_by, updated_ip, 
         form_id, form_name, fms_id, parameter_name, display_name, type, length, status)
        VALUES 
        ('$created_at', '$updated_at', '$createdBy', '$updated_by', '$updated_ip',
         '12', '$formname', '$fms_id', '$paramername', '$displayName', '$type', '$length', '1')";

        return mysqli_query($this->conn, $query);
    }

    //addColumnInTable("fms_master", "status", "VARCHAR(50) NOT NULL DEFAULT 'active'");
    public function addColumnInTable($tableName, $col = [], $type = [], $length = []) {

        if (empty($col)) {
            throw new Exception("No columns provided");
        }

        $sql = "ALTER TABLE `$tableName` ";
        $parts = [];

        for ($i = 0; $i < count($col); $i++) {
            $column = $this->spaceRemover($col[$i]);
            $parts[] = "ADD COLUMN `$column` " . $this->columnType($type[$i], $length[$i]);
        }

        $sql .= implode(", ", $parts);
        $result = mysqli_query($this->conn, $sql);
        if (!$result) {
            $error = mysqli_error($this->conn);
            $errno = mysqli_errno($this->conn);
            throw new GlobalException("MySQL Error [$errno]: $error \nQuery: $sql");
        }
        return true;
    }

    public function columnUpdate($tablename, $oldcolumn, $newcolumn = ["type"=>[], "length"=>[], "newcolumn"=>[]]) {

        $queries = [];
        for ($i = 0; $i < count($oldcolumn); $i++) {
            $old = $oldcolumn[$i];
            $new = $newcolumn['newcolumn'][$i];
            $type = $this->columnType($newcolumn['type'][$i], $newcolumn['length'][$i]);
            $queries[] = "CHANGE `$old` `$new` $type";
        }
        $sql = "ALTER TABLE `$tablename` " . implode(", ", $queries);
        return mysqli_query($this->conn, $sql);
    }

    /*
     * [
  "newcolumn" => ["age", "address"],
  "type" => ["INT", "VARCHAR"],
  "length" => [11, 255]
]
     */
    public function addMoreParameteronUpdareTime($table = "", $newcolumn = []) {
        $queries = [];
        for ($i = 0; $i < count($newcolumn); $i++) {
            $name = $newcolumn[$i];
            $type = 'varchar(255)';
            $queries[] = "ADD `$name` $type";
        }
        $sql = "ALTER TABLE `$table` " . implode(", ", $queries);


        return mysqli_query($this->conn, $sql);
    }

    public function columnType($type, $length) {
        if ($type === "3") {
            return "INT($length)";
        }
        return "VARCHAR($length)";
    }
}

class FormView{
    private $conn;
    public function __construct($conn){
        $this->conn = $conn;
    }

    public function viewFrom($data){
        $parameter_name = json_decode($data['parameter_name'], true);
        $display_name   = json_decode($data['display_name'], true);
        $type           = json_decode($data['type'], true);
        $length         = json_decode($data['length'], true);

        $total = count($parameter_name);

        for($i = 0; $i < $total; $i++){

            // Start a new row for every 2 fields
            if($i % 2 == 0){
                echo "<div class='form-group row'>";
            }

            $name  = $parameter_name[$i];
            $label = $display_name[$i];
            $t     = $type[$i];
            $len   = $length[$i];

            echo "<div class='col-md-6'>";
            echo "<label class='control-label'>{$label}</label>";
            echo $this->paramtertype($t, $name, $len);
            echo "</div>";

            // Close row after 2 columns OR at last item
            if($i % 2 == 1 || $i == $total - 1){
                echo "</div>";
            }
        }
    }

    public function loadform($id){
        $data=[];
        $result=mysqli_query($this->conn,"SELECT * FROM `form_master` where id = $id LIMIT  1");
        if($result){
            while ($row=mysqli_fetch_assoc($result)){
                $data[]=$row;
            }
        }
        return $data[0];
    }

    public function paramtertype($type, $name, $length){
        $inputType = "text";
        switch($type){
            case 1: $inputType = "text"; break;
            case 2: $inputType = "email"; break;
            case 3: $inputType = "number"; break;
            case 4: $inputType = "password"; break;
        }
        return "<input name='{$name}' type='{$inputType}' class='form-control' maxlength='{$length}' required>";
    }


    public function saveDataintable($table, $parameter, $data = [])
    {
        $columns = implode(",", $parameter);
        $escapedData = [];
        foreach ($data as $value) {
            $escapedData[] = "'" . mysqli_real_escape_string($this->conn, $value) . "'";
        }
        $values = implode(",", $escapedData);
        $sql = "INSERT INTO `$table` ($columns) VALUES ($values)";

        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

}

class FMSReports{
    private $conn;
    public function __construct($conn){
        $this->conn = $conn;
    }

    public function showReports($tablename, $filter = []){
        $startDate = $filter[0] ?? '';
        $endDate   = $filter[1] ?? '';

        // escape dates (basic safety)
        $startDate = mysqli_real_escape_string($this->conn, $startDate);
        $endDate   = mysqli_real_escape_string($this->conn, $endDate);

        $sql = "SELECT * FROM `$tablename`";

        if (!empty($startDate) && !empty($endDate)) {
            $sql .= " WHERE DATE(created_date) BETWEEN '$startDate' AND '$endDate'";
        }

        $result = mysqli_query($this->conn, $sql);
        if (!$result) {
            return false;
        }
        $data_repo=[];
        while($row=mysqli_fetch_assoc($result)){
            $data_repo[]=$row;
        }
        if(count($data_repo)>0){
            return  $data_repo;
        }else{
            return 2;
        }
    }
}

//  ---------- function programming ----
function fmsloading($link1,$id){
 $result=mysqli_query($link1,"SELECT * FROM `fms_master` where id=$id LIMIT 1");
 if($result){
     $data=mysqli_fetch_assoc($result);
     return $data;
 }
return null;
}
?>
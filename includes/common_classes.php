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
    public static function loadform($link,$formid){
        $sql="select * from `form_master` where id='$formid'";
        $result=mysqli_query($link,$sql);
        if($result){
            while ($row=mysqli_fetch_assoc($result)){
                return $row;
            }
        }
        return [];
    }

    /*
     * $remove = ['id', 'created_date', 'update_date', 'updated_by', 'updated_ip'];
     * $result = self::removeColumn($oldcolumn, $remove);
     */
    public static function removeColumn($columns = [], $remove = []){
        return array_values(array_filter($columns, function ($col) use ($remove) {
            return !in_array($col, $remove);
        }));
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
        $check=$data['check'];

        $result=mysqli_query($this->conn,"SELECT parameter_name FROM `form_master` WHERE id='$id'");
        if(!$result){
            throw new GlobalException("Column not found");
        }

        $old_db_col=[];
        while($row=mysqli_fetch_array($result)){
            $old_db_col[]=json_decode($row[0]);
        }


        $newcolumn=[
            "type"=>json_decode($type),
            "length"=>json_decode($length),
            "newcolumn"=>json_decode($paramername),
        ];



        $remove = ['id', 'created_date', 'update_date', 'updated_by', 'updated_ip'];
        $oldcolumn = array_values(array_filter($old_db_col, function($col) use ($remove) {
            return !in_array($col, $remove);
        }));


        $newcolumn_add=json_decode($paramername);

        $diff = array_values(array_diff($newcolumn_add, $oldcolumn));

        try{
            $this->addMoreParameteronUpdareTime($tablename,$diff);
        }catch (Exception $e){
            throw new GlobalException($e->getMessage());
        }

        $val_res=$this->columnUpdate_1($tablename,$old_db_col,$newcolumn);
        if($val_res){
            $query = "UPDATE form_master SET updated_date = '$updated_at',
                       updated_by = '$updatedBy',
                       updated_ip = '$updated_ip',
                       form_name = '$formname',
                       fms_id = '$fms_id',
                       parameter_name = '$paramername',
                       display_name = '$displayName',
                       type = '$type',
                       length = '$length',
                       param_require='$check'
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
        $frm_seq=$data['frm_seq'];
        $check=$data['check'];


        $created_at = date('Y-m-d H:i:s');
        $updated_at = $created_at;
        $updated_by = $createdBy;
        $updated_ip = $_SERVER['REMOTE_ADDR'];

        $query = "INSERT INTO form_master 
        (created_date, updated_date, created_by, updated_by, updated_ip, 
         form_id, form_name, fms_id, parameter_name, display_name, type, length, status,frm_seq,param_require)
        VALUES 
        ('$created_at', '$updated_at', '$createdBy', '$updated_by', '$updated_ip',
         '12', '$formname', '$fms_id', '$paramername', '$displayName', '$type', '$length', '1','$frm_seq','$check')";

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
            var_dump($old,$new,$type,$queries);
        }
        exit();
        $sql = "ALTER TABLE `$tablename` " . implode(", ", $queries);

        return mysqli_query($this->conn, $sql);
    }

    public function columnUpdate_1($tablename, $oldcolumn, $newcolumn = ["type"=>[], "length"=>[], "newcolumn"=>[]]) {
        if (isset($oldcolumn[0]) && is_array($oldcolumn[0])) {
            $oldcolumn = $oldcolumn[0];
        }
        $queries = [];

        for ($i = 0; $i < count($oldcolumn); $i++) {
            $old = $oldcolumn[$i] ?? null;
            $new = $newcolumn['newcolumn'][$i] ?? null;
            $typeId = $newcolumn['type'][$i] ?? null;
            $length = $newcolumn['length'][$i] ?? null;

            if (!$old || !$new || !$typeId) {
                continue;
            }
            $type = $this->columnType($typeId, $length);
            $old = trim($old);
            $new = trim($new);

            $queries[] = "CHANGE `$old` `$new` $type";
        }
        if (empty($queries)) {
            return false;
        }

        $sql = "ALTER TABLE `$tablename` " . implode(", ", $queries);

        $result=mysqli_query($this->conn, $sql);
        if(!$result){
            throw new GlobalException(mysqli_error($this->conn));
        }
        return $result;
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
            return "VARCHAR($length)";
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

function fileUploader($file_up)
{
    if (!isset($file_up['form_upload_file'])) {
        throw new Exception("No file provided");
    }

    $file = $file_up['form_upload_file'];


    if ($file['error'] !== 0) {
        throw new Exception("File upload error");
    }

    $filename = $file['name'];
    $tmp = $file['tmp_name'];

    $allowedExt = ['xls', 'xlsx', 'csv', 'xlsm'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {
        throw new Exception("File type mismatch Error");
    }

    $cleanName = preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', $filename);

    $today = date('YmdHis');
    $newName = $today . '_' . $cleanName;
    $uploadDir = "../ExcelExportAPI/upload/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $destination = $uploadDir . $newName;
    if (!move_uploaded_file($tmp, $destination)) {
        throw new Exception("Failed to move uploaded file");
    }
    chmod($destination, 0644);
    return $destination;
}

function loadSheets($filename){
    $identityType = PHPExcel_IOFactory::identify($filename);
    $object = PHPExcel_IOFactory::createReader($identityType);     //Ab jo file type detect hua hai uske according reader object create kiya ja raha hai.
    $object->setReadDataOnly(true); //data read karo
    $objPHPExcel = $object->load($filename); // Excel file ko memory me load
    $sheet = $objPHPExcel->getSheet(0); // first sheet
    return $sheet;
}

function sheetcolumn($sheet){
    $columns = [];

    $highestColumn = $sheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

    for($col = 0; $col < $highestColumnIndex; $col++){
        $value = $sheet->getCellByColumnAndRow($col, 1)->getValue();
        if($value !== null){
            $columns[] = trim($value);
        }
    }
    return $columns;
}

function getAllExcelData($sheet, $highestRow){
    $data = [];

    $headers = sheetcolumn($sheet);

    $highestColumn = $sheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

    // Row 2 se start (row 1 = header)
    for($row = 2; $row <= $highestRow; $row++){
        $rowData = [];

        for($col = 0; $col < $highestColumnIndex; $col++){
            $value = $sheet->getCellByColumnAndRow($col, $row)->getValue();

            // header ke naam se mapping
            if(isset($headers[$col])){
                $key = strtolower(trim($headers[$col]));
                $key = preg_replace('/\s+/', '_', $key);

                $rowData[$key] = $value;
            }
        }

        // skip empty rows (optional but important)
        if(array_filter($rowData)){
            $data[] = $rowData;
        }
    }
    return $data;
}

function normalizeColumns($columns = []){
    $clean = [];
    foreach($columns as $col){
        $col = strtolower(trim($col));
        $col = preg_replace('/\s+/', ' ', $col);
        $col = str_replace(' ', '_', $col);
        $col = preg_replace('/[^a-z0-9_]/', '', $col);
        $clean[] = $col;
    }
    return $clean;
}

class Reportuploader{
    private $conn;
    public function __construct($conn){
        $this->conn = $conn;
    }
    private function getAllTableColumn($tablename,$remove=['id','created_date','update_date','updated_by','updated_ip']){
        $columns = [];
        $result = mysqli_query($this->conn, "SHOW COLUMNS FROM `$tablename`");

        if($result){
            while($row = mysqli_fetch_assoc($result)){
                $columns[] = $row['Field'];
            }
        }
        $columns=FMsBasicOperation::removeColumn($columns,$remove);
        return $columns;
    }

    public function validateSheetColumn($tablename, $sheetColumns = []){

        if(empty($sheetColumns)){
            throw new Exception("Sheet columns empty");
        }


        $dbColumns = $this->getAllTableColumn($tablename);


        $sheetColumns=normalizeColumns($sheetColumns);



        if(empty($dbColumns)){
            throw new Exception("Table columns not fetch from db");
        }


        $sheetColumns = array_map('strtolower', $sheetColumns);
        $dbColumns = array_map('strtolower', $dbColumns);


        $missing = array_diff($dbColumns, $sheetColumns);
        $extra = array_diff($sheetColumns, $dbColumns);


        if(!empty($missing)){
            throw new Exception("Missing columns: " . implode(", ", $missing));
        }

        if(!empty($extra)){
            throw new Exception("Invalid/Extra columns: " . implode(", ", $extra));
        }


        return true;
    }

    public function insertData($data, $table, $updateby, $updateip){

        $columns = $this->getAllTableColumn($table, ['id','created_date']);

        mysqli_commit($this->conn,false);
        $error_data = [];

        $saveData=false;

        foreach($data as $index => $row){

            $row['updated_date'] = date('Y-m-d H:i:s');
            $row['updated_by'] = $updateby;
            $row['updated_ip'] = $updateip;

            $fields = [];
            $values = [];

            foreach($columns as $col){
                if(isset($row[$col])){
                    $fields[] = $col;
                    $values[] = "'" . addslashes($row[$col]) . "'";
                }
            }

            $fieldList = implode(',', $fields);
            $valueList = implode(',', $values);

            $query = "INSERT INTO $table ($fieldList) VALUES ($valueList)";

            $result = mysqli_query($this->conn, $query);

            if(!$result){
                $error_data[] = [
                    'row_index' => $index,
                    'data' => $row,
                    'error' => 'Some things Wrong in Uploaded Data'
                ];
            }
        }

        if(!empty($error_data)){
            mysqli_rollback($this->conn,false);
            throw new Exception(json_encode($error_data));
        } else {
            mysqli_commit($this->conn,false);
            $saveData=true;
        }

        return $saveData;
    }


}


?>
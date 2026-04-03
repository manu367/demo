<?php

class FMSClone{
    private $fms_id;
    private $conn;
    private $tableName;
    public function __construct($conn,$fmsid){
        $this->fms_id=$fmsid;
        $this->conn=$conn;
    }
    public function setTableName($tableName){
        $this->tableName=$tableName;
    }
    public function fmsClone($fmsName, $fms_details='', $fms_steps='', $fms_totalform='', $fmsIP) {

        $old_fms = $this->getAllData();

        if ($old_fms === null) {
            throw new GlobalException("Id is Wrong");
        }

        // old columns lo
        $old_keys = array_keys($old_fms);
        $old_keys = FMsBasicOperation::removeColumn($old_keys, ['id']);

        $columns = implode(",", $old_keys);

        $values = [];

        foreach ($old_keys as $key) {

            $val = $old_fms[$key];

            //  override values
            if ($key === 'table_name') {
                $val='fms_'.$this->tableName;
                $this->tableName=$val;
                $this->createTable($val);
            }

            if ($key === 'fmsname') {
                $val = $fmsName;
            }

//            if ($key === 'details') {
//                $val = $fms_details;
//            }
//
//            if ($key === 'steps') {
//                $val = $fms_steps;
//            }
//
//            if ($key === 'total_form') {
//                $val = $fms_totalform;
//            }

            if ($key === 'updated_ip') {
                $val = $fmsIP;
            }

            $val = mysqli_real_escape_string($this->conn, $val);
            $values[] = "'$val'";
        }

        $values_str = implode(",", $values);

        $sql = "INSERT INTO `fms_master` ($columns) VALUES ($values_str)";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            throw new GlobalException("FMS Clone Failed: " . mysqli_error($this->conn));
        }

        $newFmsId = mysqli_insert_id($this->conn);

        return $newFmsId;
    }

    public function formCloningStart($newFmsid){
        // form-cloneing start
        $formclone=new FormClone($this->conn,$this->fms_id);

        $data_form=$formclone->getAllFormData();

        $flag=false;

        for($i=0;$i<count($data_form);$i++){

            $split_item=$formclone->splitParamter($data_form[$i]);
            $alter_table=$formclone->addColumnInTable($this->tableName,$split_item['paramter_name'],$split_item['type'],$split_item['length']);
            $query=$formclone->insertIntoFormMaster($data_form[$i],$newFmsid);
            $flag=true;
        }
        return $flag;
    }

    private function getAllData(){
        return FMsBasicOperation::loadFMS($this->conn,$this->fms_id);
    }

    public function createTable($name)
    {
        $tableName=$name;
        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            update_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by varchar(10),
    updated_ip varchar(40)
        ) ENGINE=InnoDB";

        $result=mysqli_query($this->conn,$sql);
        return $result;
    }

}

class FormClone{
    private $conn;
    private $fms_id;
    public function __construct($conn,$fmsid){
        $this->conn=$conn;
        $this->fms_id=$fmsid;
    }
    public function insertIntoFormMaster($data,$newFmsId){
        $created_at=$data['created_date'];
        $updated_at=$data['updated_date'];
        $createdBy=$data['created_by'];
        $updated_by=$data['updated_by'];
        $updated_ip=$data['updated_ip'];
        $formname=$data['form_name'];
        $fms_id=$newFmsId;
        $paramername=$data['parameter_name'];
        $displayName=$data['display_name'];
        $type=$data['type'];
        $length=$data['length'];
        $frm_seq=$data['frm_seq'];
        $check=$data['param_require'];

        $query = "INSERT INTO form_master 
        (created_date, updated_date, created_by, updated_by, updated_ip, 
         form_id, form_name, fms_id, parameter_name, display_name, type, length, status,frm_seq,param_require)
        VALUES 
        ('$created_at', '$updated_at', '$createdBy', '$updated_by', '$updated_ip',
         '12', '$formname', '$fms_id', '$paramername', '$displayName', '$type', '$length', '1','$frm_seq','$check')";

        return mysqli_query($this->conn,$query);
    }
    public function getAllFormData(){
        $sql="SELECT * FROM `form_master` WHERE fms_id='$this->fms_id'";
        $result=mysqli_query($this->conn,$sql);
        $count=mysqli_num_rows($result);
        $data=[];
        if($result && $count>0){
            while ($row=mysqli_fetch_assoc($result)){
                $data[]=$row;
            }
        }
        return $data;
    }

    public function splitParamter($data){
        return [
            "paramter_name"=>json_decode($data['parameter_name']),
            "type"=>json_decode($data['type']),
            "length"=>json_decode($data['length']),
        ];
    }

    public function addColumnInTable($tableName, $col = [], $type = [], $length = []) {

        $sql = "ALTER TABLE `$tableName` ";
        $parts = [];

        for ($i = 0; $i < count($col); $i++) {
            $column = $this->spaceRemover($col[$i]);

            $parts[] = "ADD COLUMN `$column` " . $this->columnType($type[$i], $length[$i]);
        }

        $sql .= implode(", ", $parts);

        return mysqli_query($this->conn, $sql);
    }
    private function spaceRemover($name){
        $name = trim($name);
        $tableName = str_replace(' ', '_', $name);
        $tableName = preg_replace('/[^A-Za-z0-9_]/', '', $tableName);
        $tableName=strtolower($tableName);
        return $tableName;
    }

    public function columnType($type, $length) {
        if ($type === "3") {
            return "VARCHAR($length)";
        }
        return "VARCHAR($length)";
    }

}






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
    public function tablenameAlreadyExists($db,$tablname){
        $tablname=strtolower($tablname);
        $tablname='fms_'.trim($tablname);
        $sql="SELECT COUNT(*) as total FROM information_schema.tables WHERE table_schema = '$db' AND table_name = '$tablname'";
        $result=mysqli_query($this->conn,$sql);
        $row=mysqli_fetch_assoc($result);
        return $row['total'];
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
    public function updateForm($id, $fms_id, $data = [], $updatedBy, $tablename = "")
    {
        $flag=false;
        if (!$this->isDbColumnValid($data)) {
            throw new GlobalException("Please Refresh Page");
        }



        $col_name = [];
        $new_name = [];
        $types    = [];
        $length   = [];

        foreach ($data['new'] as $formunits) {

            $oldCol = $formunits->old_column ?? null;
            $newCol = $formunits->parameter;

            // agar old column exist karta hai aur change hua hai tabhi ALTER kare
            if ($oldCol !== null && $oldCol !== $newCol) {
                $col_name[] = $oldCol;          // old column name
                $new_name[] = $newCol;          // new column name
                $types[]    = 'varchar';
                $length[]   = $formunits->length;
            }

            // agar sirf type/length change hua ho (naam same hai)
            elseif ($oldCol !== null && $oldCol === $newCol) {
                $col_name[] = $oldCol;
                $new_name[] = $oldCol; // same name
                $types[]    = 'varchar';
                $length[]   = $formunits->length;
            }
        }

        if (count($col_name) > 0) {
            $sql_query = $this->changeColumnNameinDb(
                $tablename,
                $col_name,
                $new_name,
                $types,
                $length
            );

            if (!mysqli_query($this->conn, $sql_query)) {
                throw new GlobalException("Alter failed: " . mysqli_error($this->conn) . " | Query: $sql_query");
            }
        }


        // insert final data here
        try{
            $result=$this->updateFormMaster_1($data['formid'],$data['new'],$data['frm_seq']);
            $flag=true;
        }catch (Exception $e){
            throw new GlobalException($e->getMessage());
        }
        return $flag;
    }
    public function isDbColumnValid($data) {
        $formid = $data['formid'];
        $query = "SELECT parameter_name FROM form_master WHERE id = '$formid'";
        $result = mysqli_query($this->conn, $query);

        if (!$result || mysqli_num_rows($result) == 0) {
            throw new GlobalException("Form not found");
        }
        $row = mysqli_fetch_assoc($result);

        $currentColNme = json_decode($row['parameter_name'], true);

        if (!is_array($currentColNme)) {
            throw new GlobalException("Invalid JSON in DB");
        }

        if (!isset($data['old_col']) || !is_array($data['old_col'])) {
            throw new GlobalException("Invalid input data");
        }
        if ($currentColNme !== $data['old_col']) {
            throw new GlobalException("Column Name Mismatch");
        }
        return true;
    }

    private function updateFormMaster_1($formid, $data,$frm_seq)
    {
        $parameter   = [];
        $displayname = [];
        $type        = [];
        $require     = [];
        $length      = [];


        foreach ($data as $formunits) {
            $parameter[]   = $formunits->parameter;
            $displayname[] = $formunits->value;
            $type[]        = $formunits->type;
            $require[]     = $formunits->require;
            $length[]      = $formunits->length;
        }

        $parameter   = json_encode($parameter);
        $displayname = json_encode($displayname);
        $type        = json_encode($type);
        $require     = json_encode($require);
        $length      = json_encode($length);

        $sql = "UPDATE form_master SET 
                parameter_name = '$parameter',
                display_name = '$displayname',
                type = '$type',
                param_require = '$require',
                length = '$length',
                frm_seq='$frm_seq'
            WHERE id = '$formid'";

        return mysqli_query($this->conn, $sql);
    }

    public function changeColumnNameinDb($table, $colNames = [], $newNames = [], $types = [], $lengths = []) {
        if (
            count($colNames) !== count($newNames) ||
            count($colNames) !== count($types) ||
            count($colNames) !== count($lengths)
        ) {
            throw new GlobalException("All Data must have same length");
        }

        $queries = [];

        foreach ($colNames as $i => $col) {
            $new = $newNames[$i];
            $type = strtoupper($types[$i]);

            $length = !empty($lengths[$i]) ? "(" . (int)$lengths[$i] . ")" : "";
            $queries[] = "CHANGE `$col` `$new` $type$length";
        }

        $query = "ALTER TABLE `$table` " . implode(", ", $queries);
        return $query;
    }

    public function addnewColumnInDb($table, $col = []) {

        if (empty($table) || empty($col)) {
            throw new Exception("Invalid input");
        }
        $col=$col['column'];

        foreach ($col as $item) {
            if (empty($item->parameter)) {
                continue;
            }
            $columnName = mysqli_real_escape_string($this->conn, $item->parameter);

            $checkQuery = "SHOW COLUMNS FROM `$table` LIKE '$columnName'";
            $result = mysqli_query($this->conn, $checkQuery);

            if ($result && mysqli_num_rows($result) > 0) {
                $length=$item->length;
                $alterQuery = "ALTER TABLE `$table` MODIFY `$columnName` VARCHAR(255) NULL";
            } else {
                $length=$item->length;
                $alterQuery = "ALTER TABLE `$table` ADD `$columnName` VARCHAR($length) NULL";
            }

            if (!mysqli_query($this->conn, $alterQuery)) {
                throw new GlobalException("Error altering table: " . mysqli_error($this->conn));
            }
        }
        return true;
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

        try{
            $result=mysqli_query($this->conn, $query);
            return true;
        }catch (Exception $e){

        }

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
        $require        =json_decode($data['param_require'],true);

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
            $req   = $require[$i];

            $reqDiv=$req==='0'?'':'<span class="red_small">*</span>';
            $reqRequire=$req==='0'?'':'required';

            echo "<div class='col-md-6'>";
            echo "<div>";
            echo "<label class='control-label'>{$label} {$reqDiv}</label>";
            echo $this->paramtertype($t, $name, $len,$reqRequire);
            echo "</div>";
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

    public function paramtertype($type, $name, $length,$reqRequire)
    {
        $inputType = "text";
        $extraAttr = "";

        switch ($type) {
            case 1:
                $inputType = "text";
                $extraAttr = "maxlength='{$length}'";
                break;
            case 2:
                $inputType = "email";
                $extraAttr = "maxlength='{$length}'";
                break;
            case 3:
                $inputType = "number";
                $extraAttr = "maxlength='{$length}' pattern='[0-9]{1,{$length}}' oninput=\"this.value=this.value.replace(/[^0-9]/g,'').slice(0,{$length})\"";
                break;

            case 4:
                $inputType = "password";
                $extraAttr = "maxlength='{$length}'";
                break;
            case 5:
                $inputType = "tel";
                $extraAttr = "maxlength='{$length}'";
                break;
            case 6:
                $inputType = "date";
                $extraAttr = "maxlength='{$length}'";
                break;

            default : break;
        }

        return "<input name='{$name}' type='{$inputType}' class='form-control' {$extraAttr} {$reqRequire}>";
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


/**
 * Class FMSReports
 *
 * Handles fetching report data from database tables
 * with optional date range filtering.
 */
class FMSReports{
    /**
     * @var mysqli Database connection instance
     */
    private $conn;
    /**
     * Constructor to initialize database connection.
     *
     * @param mysqli $conn Active MySQLi connection
     */
    public function __construct($conn){
        $this->conn = $conn;
    }

    /**
     * Fetch reports from a given table with optional date filtering.
     *
     * This method retrieves all records from the specified table.
     * If a date range is provided, it filters results based on
     * the `created_date` column.
     *
     * Date filter format:
     * [
     *     0 => start date (YYYY-MM-DD),
     *     1 => end date (YYYY-MM-DD)
     * ]
     *
     * Behavior:
     * - Returns array of records if data exists
     * - Returns 2 if no records found
     * - Returns false if query fails
     *
     * Example:
     * $reports = $obj->showReports('users', ['2025-01-01', '2025-01-31']);
     *
     * @param string $tablename Name of the table to query
     * @param array  $filter    Optional date range [startDate, endDate]
     *
     * @return array|int|false
     */
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

/**
 * Fetch a single record from the fms_master table by ID.
 *
 * This function executes a SELECT query on the `fms_master` table
 * using the provided ID and returns the first matching row.
 *
 * If a record is found, it is returned as an associative array.
 * If no record is found or query fails, null is returned.
 *
 * Example:
 * $data = fmsloading($conn, 5);
 *
 * @param mysqli $link1 Database connection object
 * @param int    $id    Record ID to fetch
 *
 * @return array|null Associative array of the record, or null if not found
 */
function fmsloading($link1,$id){
    $result=mysqli_query($link1,"SELECT * FROM `fms_master` where id=$id LIMIT 1");
    if($result){
        $data=mysqli_fetch_assoc($result);
        return $data;
    }
    return null;
}


/**
 * Handle file upload, validate it, and store it on the server.
 *
 * This function processes an uploaded file from a form input
 * named 'form_upload_file'. It validates the file, sanitizes
 * the filename, and moves it to a target upload directory.
 *
 * Key operations:
 * - Checks if file exists in request
 * - Validates upload errors
 * - Allows only specific file extensions (xls, xlsx, csv, xlsm)
 * - Sanitizes filename to prevent unsafe characters
 * - Renames file using timestamp to avoid conflicts
 * - Creates upload directory if it doesn't exist
 * - Moves file to destination and sets permissions
 *
 * Example:
 * $path = fileUploader($_FILES);
 *
 * @param array $file_up The $_FILES superglobal array
 *
 * @return string Full path to the uploaded file
 *
 * @throws GlobalException If file is missing, invalid, or upload fails
 */
function fileUploader($file_up)
{
    if (!isset($file_up['form_upload_file'])) {
        throw new GlobalException("No file provided");
    }

    $file = $file_up['form_upload_file'];


    if ($file['error'] !== 0) {
        throw new GlobalException("File upload error");
    }

    $filename = $file['name'];
    $tmp = $file['tmp_name'];

    $allowedExt = ['xls', 'xlsx', 'csv', 'xlsm'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {
        throw new GlobalException("File type mismatch Error");
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
        throw new GlobalException("Failed to move uploaded file");
    }
    chmod($destination, 0644);
    return $destination;
}

/**
 * Load an Excel file and return the first worksheet.
 *
 * This function detects the file type automatically,
 * creates the appropriate reader, and loads the Excel
 * file into memory. It returns only the first sheet.
 *
 * Data is read in "read-only" mode for better performance.
 *
 * Supported formats depend on PHPExcel (e.g., XLS, XLSX, CSV).
 *
 * Example:
 * $sheet = loadSheets('file.xlsx');
 *
 * @param string $filename Path to the Excel file
 *
 * @return PHPExcel_Worksheet The first worksheet object
 *
 * @throws PHPExcel_Reader_Exception If file type is not supported
 * @throws PHPExcel_Exception If file cannot be loaded
 */
function loadSheets($filename){
    $identityType = PHPExcel_IOFactory::identify($filename);
    $object = PHPExcel_IOFactory::createReader($identityType);     //Ab jo file type detect hua hai uske according reader object create kiya ja raha hai.
    $object->setReadDataOnly(true); //data read karo
    $objPHPExcel = $object->load($filename); // Excel file ko memory me load
    $sheet = $objPHPExcel->getSheet(0); // first sheet
    return $sheet;
}

/**
 * Retrieve column headers from the first row of an Excel sheet.
 *
 * This function reads the first row (row 1) of the given sheet
 * and extracts all column header values into an array.
 * Empty/null cells are ignored.
 *
 * Each header value is trimmed to remove extra whitespace.
 *
 * Example:
 * Row 1: [" First Name ", "Email", "Phone Number"]
 * Output: ["First Name", "Email", "Phone Number"]
 *
 * @param object $sheet Excel sheet object (PHPExcel_Worksheet)
 *
 * @return array List of column header names
 */
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

/**
 * Extract all data rows from an Excel sheet and map them to header-based keys.
 *
 * This function reads data from a given sheet starting from row 2
 * (assuming row 1 contains headers). Each row is converted into
 * an associative array where keys are derived from the header row.
 *
 * Header keys are normalized by:
 * - Trimming whitespace
 * - Converting to lowercase
 * - Replacing spaces with underscores
 *
 * Empty rows are skipped automatically.
 *
 * Example output:
 * [
 *     ["first_name" => "Manu", "email" => "demo@example.com"],
 *     ["first_name" => "Manu", "email" => "demo@example.com"]
 * ]
 *
 * @param object $sheet       Excel sheet object (PHPExcel_Worksheet)
 * @param int    $highestRow  Highest row number in the sheet
 *
 * @return array List of associative arrays representing each row
 */
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

/**
 * Normalize column names into a clean, database-friendly format.
 *
 * This function processes an array of column names and applies
 * the following transformations:
 * - Trims leading and trailing whitespace
 * - Converts all characters to lowercase
 * - Replaces multiple spaces with a single space
 * - Replaces spaces with underscores
 * - Removes all non-alphanumeric characters except underscores
 *
 * Example:
 * Input:  [" First Name ", "Email Address", "User@ID!"]
 * Output: ["first_name", "email_address", "userid"]
 *
 * @param array $columns List of column names to normalize
 *
 * @return array Cleaned and normalized column names
 */
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
            throw new GlobalException("Missing columns: " . implode(", ", $missing));
        }
        if(!empty($extra)){
            throw new GlobalException("Invalid/Extra columns: " . implode(", ", $extra));
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


/**
 * Interface Permissions
 *
 * Marker interface for permission-related classes.
 * Can be extended in future to enforce permission contracts.
 */
interface Permissions{}

/**
 * Class UpdatePermission
 *
 * Responsible for:
 * - Rendering permission UI (main tabs + sub tabs)
 * - Managing user access (access_tab table)
 * - Managing operation-level permissions (operation_rights table)
 * - Updating and resetting permissions
 *
 * Tables Used:
 * - tab_master
 * - access_tab
 * - operation_rights
 *
 * Dependencies:
 * - mysqli connection
 * - TabPermission class (for encapsulating permission data)
 * - GlobalException class (for error handling)
 */
class UpdatePermission implements Permissions{
    /**
     * @var mysqli Database connection instance
     */
    public $conn;
    /**
     * @var string|null Current user ID
     */
    public $userid = null;
    public function __construct(){}
    /**
     * Set database connection
     *
     * @param mysqli $con
     * @return void
     */
    public function setConnection($con){
        $this->conn = $con;
    }
    /**
     * Set current user ID
     *
     * @param string $userid
     * @return void
     */
    public function setUserid($userid){
        $this->userid = $userid;
    }

    /**
     * Generate HTML for all main tabs and their sub-tabs
     *
     * - Fetches active admin tabs from tab_master
     * - Groups by main tab
     * - Calls sub-tab renderer
     *
     * @return string HTML output
     */
    /**
     * Generate HTML for sub-tabs under a main tab
     *
     * Includes:
     * - Access checkbox (access_tab)
     * - Operation rights checkboxes (operation_rights)
     *
     * @param string $maintabname Main tab name
     * @param int    $j           Sequence index for grouping
     *
     * @return string HTML output
     */
    public function printMainTabName(){
        $html = "";

        $sql = "select maintabname,maintabicon from tab_master where status='1' and tabfor='admin' group by maintabname order by maintabseq";

        $rs = mysqli_query($this->conn, $sql);

        if($rs && mysqli_num_rows($rs) > 0){
            $j = 1;
            while($row = mysqli_fetch_assoc($rs)){

                // main tab aane start -> yha se kar rha hu
                $html .= "<tr>
                    <td style='border:none' class='bg-success'>
                        <i class='fa {$row['maintabicon']} fa-lg'></i>&nbsp;{$row['maintabname']}
                    </td>
                </tr>";

                // sub tab load starting yha se
                $html .= $this->showSubTabName($row['maintabname'], $j);

                $j++;
            }
        }

        return $html;
    }

    private function showSubTabName($maintabname, $j){
        $html = "";
        $sql = "SELECT tabid, subtabname, subtabicon 
                FROM tab_master 
                WHERE maintabname='$maintabname' 
                AND status='1' AND tabfor='admin' 
                ORDER BY subtabname";

        $rs = mysqli_query($this->conn, $sql);

        if($rs && mysqli_num_rows($rs) > 0){
            $i = 1;
            while($row = mysqli_fetch_assoc($rs)){
                // ACCESS CHECK from access tab -jab bhi status 1 hoga tabi show hoga
                // return tabid
                $acc_sql = "SELECT tabid FROM access_tab 
                            WHERE status='1' 
                            AND tabid='{$row['tabid']}' 
                            AND userid='{$this->userid}'";

                $state_acc = mysqli_query($this->conn, $acc_sql);
                $checked_tab = (mysqli_num_rows($state_acc) > 0) ? "checked" : "";

                // OPERATION RIGHTS
                $opr_sql = "SELECT * FROM operation_rights 
                            WHERE tabid='{$row['tabid']}' 
                            AND userid='{$this->userid}'";

                $res_opr = mysqli_query($this->conn, $opr_sql);
                $opr = mysqli_fetch_assoc($res_opr);

                $line_seq = $j . "_" . $i;
//                <label class="switch">
//                                <input type="checkbox" name="check_box_manu" value="2">
//                                <span class="slider"></span>
//                            </label>
                $html .= "<tr>
                    <td>
                        <input type='checkbox' name='report[]' value='{$row['tabid']}' $checked_tab>
                        &nbsp;<i class='fa {$row['subtabicon']} fa-lg'></i>&nbsp;{$row['subtabname']}
                    </td>

                    <td><input type='checkbox' name='add_rgt[{$row['tabid']}]' ".($opr['add_rgt']=='Y'?'checked':'')."></td>
                    <td><input type='checkbox' name='edit_rgt[{$row['tabid']}]' ".($opr['edit_rgt']=='Y'?'checked':'')."></td>
                    <td><input type='checkbox' name='view_rgt[{$row['tabid']}]' ".($opr['view_rgt']=='Y'?'checked':'')."></td>
                    <td><input type='checkbox' name='cancel_rgt[{$row['tabid']}]' ".($opr['cancel_rgt']=='Y'?'checked':'')."></td>
                    <td><input type='checkbox' name='print_rgt[{$row['tabid']}]' ".($opr['print_rgt']=='Y'?'checked':'')."></td>
                    <td><input type='checkbox' name='excel_rgt[{$row['tabid']}]' ".($opr['download_rgt']=='Y'?'checked':'')."></td>

                    <td>";
                if($row['apply_approval'] == ""){
                    $html .= "<input type='checkbox' name='app_rgt[{$row['tabid']}]' ".($opr['approval_rgt']=='Y'?'checked':'').">";
                }
                $html .= "</td>

                    <td>
                        <input type='checkbox' name='price_rgt[{$row['tabid']}]' ".($opr['block_price']=='Y'?'checked':'').">
                    </td>
                </tr>";

                $i++;
            }
        }

        return $html;
    }

    /**
     * Update user permissions based on submitted form data
     *
     * Workflow:
     * 1. Reset all tabs (set status = 0)
     * 2. Loop through selected tabs
     * 3. Create TabPermission object
     * 4. Update access_tab
     * 5. Validate permission object
     * 6. Update operation_rights
     *
     * @param array $data Form data ($_POST)
     *
     * @return bool True on success
     * @throws GlobalException on failure
     */
    public function updatePermission($data){
        $permissions = [];
        $flag = false;

        if ($this->resetAllTabs($this->userid) && $this->resetAllOperationRight($this->userid)) {

            foreach ($data['report'] as $tabid) {

                $tabpermission = new TabPermission(
                    $tabid,
                    isset($data['add_rgt'][$tabid]),
                    isset($data['edit_rgt'][$tabid]),
                    isset($data['view_rgt'][$tabid]),
                    isset($data['cancel_rgt'][$tabid]),
                    isset($data['print_rgt'][$tabid]),
                    isset($data['excel_rgt'][$tabid]),
                    isset($data['app_rgt'][$tabid]),
                    isset($data['price_rgt'][$tabid])
                );

                // Always set selected tab = 1
                $giveAccess = $this->updateaccesstab($this->userid, $tabid, '1');

                if (!$giveAccess) {
                    throw new GlobalException('Access table update failed');
                }

                $tabpermission->validate();

                $updateRights = $this->updateOperationRight($this->userid, $tabpermission);

                if (!$updateRights) {
                    throw new GlobalException('Operation rights update failed');
                }

                $permissions[] = $tabpermission;
                $flag = true;
            }
        } else {
            throw new GlobalException('Failed to reset tabs');
        }

        return $flag;
    }
    /**
     * Insert or update access_tab entry
     *
     * - If record exists → UPDATE
     * - Else → INSERT
     *
     * @param string $userid
     * @param int    $tabid
     * @param int    $status (0 or 1)
     *
     * @return bool|mysqli_result
     * @throws GlobalException
     */
    public function updateaccesstab($userid, $tabid = 0, $status = 0)
    {
        $checkSql = "SELECT 1 FROM access_tab 
                 WHERE userid = '$userid' AND tabid = '$tabid'";

        $result = mysqli_query($this->conn, $checkSql);

        if (!$result) {
            throw new GlobalException('Error checking access_tab ' . __LINE__);
        }

        if (mysqli_num_rows($result) > 0) {

            $sql = "UPDATE access_tab 
                SET status = '$status' 
                WHERE userid = '$userid' AND tabid = '$tabid'";

        } else {

            $sql = "INSERT INTO access_tab (userid, tabid, status) 
                VALUES ('$userid', '$tabid', '$status')";
        }

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            throw new GlobalException('Error updating access_tab ' . __LINE__);
        }

        return $result;
    }
    /**
     * Insert or update operation_rights entry
     *
     * - If record exists → UPDATE
     * - Else → INSERT
     *
     * @param string        $userid
     * @param TabPermission $operation Permission object
     *
     * @return bool|mysqli_result
     * @throws GlobalException
     */
    public function updateOperationRight($userid, TabPermission $operation) {

        $checkSql = "SELECT 1 FROM operation_rights 
                 WHERE userid = '".$userid."' 
                 AND tabid = '".$operation->tabid."'";

        $checkResult = mysqli_query($this->conn, $checkSql);

        if (!$checkResult) {
            throw new GlobalException('Error checking operation_rights ' . __LINE__);
        }

        if (mysqli_num_rows($checkResult) > 0) {

            $sql = "UPDATE operation_rights SET 
                add_rgt = '".$operation->add."',
                edit_rgt = '".$operation->edit."', 
                view_rgt = '".$operation->view."', 
                cancel_rgt = '".$operation->cancel."', 
                print_rgt = '".$operation->print."', 
                download_rgt = '".$operation->report."',
                approval_rgt = '".$operation->approval."', 
                block_price = '".$operation->price_display."' 
            WHERE userid = '".$userid."' 
            AND tabid = '".$operation->tabid."'";

        } else {

            $sql = "INSERT INTO operation_rights 
            (userid, tabid, add_rgt, edit_rgt, view_rgt, cancel_rgt, print_rgt, download_rgt, approval_rgt, block_price)
            VALUES (
                '".$userid."',
                '".$operation->tabid."',
                '".$operation->add."',
                '".$operation->edit."',
                '".$operation->view."',
                '".$operation->cancel."',
                '".$operation->print."',
                '".$operation->report."',
                '".$operation->approval."',
                '".$operation->price_display."'
            )";
        }

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            throw new GlobalException('Error updating operation_rights ' . __LINE__);
        }

        return $result;
    }
    /**
     * Reset all tab access for a user
     *
     * Sets all access_tab.status = 0
     *
     * @param string $userid
     *
     * @return bool|mysqli_result
     */
    public function resetAllTabs($userid){
        $sql = "UPDATE access_tab 
                SET status = '0' 
                WHERE userid = '$userid'";
        return mysqli_query($this->conn,$sql);
    }
    public function resetAllOperationRight($userid){
        $sql = "UPDATE operation_rights 
                SET add_rgt='N',
                    edit_rgt='N',
                    view_rgt='N',cancel_rgt='N',
                    print_rgt='N',
                    download_rgt='N',
                    approval_rgt='N',
                    block_price='N'
                WHERE userid = '$userid'";
        return mysqli_query($this->conn,$sql);
    }

    public function printfmsName(){
        $html = "<div class='tab-grid' style='margin-top: 10px;margin-bottom: 10px;'>";

        $sql = "SELECT id, fmsname FROM fms_master WHERE status='1' ORDER BY fmsname";
        $result = mysqli_query($this->conn, $sql);

        if(!$result){
            return "<h1>Something went wrong</h1>";
        }

        if(mysqli_num_rows($result) === 0){
            return "<h1>FMS is Empty</h1>";
        }

        while ($row = mysqli_fetch_assoc($result)){

            $acc_sql = "SELECT fmsid FROM access_fms 
                    WHERE status='1' 
                    AND fmsid='{$row['id']}' 
                    AND userid='{$this->userid}'";

            $state_acc = mysqli_query($this->conn, $acc_sql);
            $checked = (mysqli_num_rows($state_acc) > 0) ? "checked" : "";

            $html .= "<label class='tab-item'>
                <input type='checkbox' name='fms[]' value='{$row['id']}' $checked>
                &nbsp;<i class='fa fa-folder fa-lg'></i>&nbsp;{$row['fmsname']}({$row['id']})
                </label>";
        }

        $html.="</div>";
        return $html;
    }

    public function updateFMSPermission($fms_id, $userid){

        $check = "SELECT id FROM access_fms WHERE userid='$userid' AND fmsid='$fms_id'";
        $res = mysqli_query($this->conn, $check);

        if(mysqli_num_rows($res) > 0){
            $sql = "UPDATE access_fms 
                SET status='1', updated_at=current_timestamp() 
                WHERE userid='$userid' AND fmsid='$fms_id'";
        } else {
            $sql = "INSERT INTO access_fms (userid, fmsid, status) 
                VALUES ('$userid', '$fms_id', '1')";
        }

        return mysqli_query($this->conn, $sql);
    }
    public function resentAllFMSPermission($userid){
        $sql="UPDATE `access_fms` SET `status` = '0' WHERE userid='$userid'";
        return mysqli_query($this->conn,$sql);
    }

}


/**
 * Class PermissionManager
 *
 * Handles user permission checks for different operations based on tab/module ID.
 * This class centralizes all permission-related logic such as add, edit, view,
 * cancel, approval, print, and download rights.
 *
 * It extends the UpdatePermission class (assumed to manage permission updates).
 *
 * Table used: operation_rights
 *
 * Columns used:
 * - add_rgt
 * - edit_rgt
 * - view_rgt
 * - cancel_rgt
 * - print_rgt
 * - download_rgt
 * - approval_rgt
 *
 * Each column stores 'Y' (allowed) or 'N' (not allowed).
 */
class PermissionManager extends UpdatePermission {

    public static function accessDenied($pid,$hid,$backfile){
        echo ' <div  style="display: flex;justify-content: center;padding: 20px;">
                    <div class="d-flex justify-content-center align-items-center" style="height:70vh;">
                        <div class="card shadow-lg text-center" style="max-width: 420px; border-radius:15px;">
                            <div class="card-body">
                                <div style="font-size:60px; color:#dc3545;">
                                    <i class="fa fa-lock"></i>
                                </div>
                                <h3 class="mt-3" style="font-weight:600;">Access Denied</h3>
                                <p class="text-muted mt-2">
                                    You don’t have permission to access this page.<br>
                                    Please contact your administrator if you believe this is a mistake.
                                </p>

                                <div class="mt-4">
                                    <a href="'.$backfile.'?pid='.$pid.'&hid='.$hid.'" class="btn btn-primary">
                                        <i class="fa fa-arrow-left"></i> Go Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    /**
     * Check whether a given tab ID exists for a specific user.
     *
     * @param mysqli $link   Database connection
     * @param string $userid User ID
     * @param int    $tabid  Tab ID
     *
     * @return bool Returns true if record exists, otherwise false
     */
    private static function check_tabid_exist_or_not($link,$userid,$tabid){
        $check_sql="SELECT COUNT(*) as total FROM operation_rights WHERE userid = '$userid' AND tabid ='$tabid' LIMIT 1";
        $result_check=mysqli_query($link,$check_sql);
        if(!$result_check){
            return false;
        }
        $row = mysqli_fetch_assoc($result_check);
        return ($row['total'] > 0);
    }

    /**
     * Generic permission checker.
     *
     * Validates tab ID and checks if a specific permission column is enabled.
     *
     * @param mysqli $link    Database connection
     * @param string $userid  User ID
     * @param int    $tabid   Tab ID
     * @param string $column  Permission column name
     *
     * @return bool Returns true if permission is granted ('Y'), otherwise false
     */
    private static function checkPermission($link, $userid, $tabid, $column){

        if($tabid === null){
            return false;
        }

        if (!ctype_digit($tabid)) {
            return false;
        }


        $tabid = (int)$tabid;

        if(!self::check_tabid_exist_or_not($link,$userid,$tabid)){
            return false;
        }

        $query = "SELECT $column FROM operation_rights WHERE userid = '$userid' AND tabid ='$tabid' LIMIT 1";
        $result = mysqli_query($link, $query);

        if (!$result) {
            return false;
        }

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return ($row[$column] === 'Y');
        }

        return false;
    }

    /**
     * Check Add permission.
     *
     * @param mysqli $link
     * @param string $userid
     * @param int|null $tabid
     *
     * @return bool
     */
    public static function checkaddRights($link, $userid, $tabid=null){
        return self::checkPermission($link, $userid, $tabid, 'add_rgt');
    }

    /**
     * Check Edit permission.
     *
     * @param mysqli $link
     * @param string $userid
     * @param int $tabid
     *
     * @return bool
     */
    public static function checkEditRights($link, $userid, $tabid){
        return self::checkPermission($link, $userid, $tabid, 'edit_rgt');
    }

    /**
     * Check View permission.
     *
     * @param mysqli $link
     * @param string $userid
     * @param int $tabid
     *
     * @return bool
     */
    public static function checkViewRights($link, $userid, $tabid){
        return self::checkPermission($link, $userid, $tabid, 'view_rgt');
    }

    /**
     * Check Cancel permission.
     *
     * @param mysqli $link
     * @param string $userid
     * @param int $tabid
     *
     * @return bool
     */
    public static function checkcancelRights($link, $userid, $tabid){
        return self::checkPermission($link, $userid, $tabid, 'cancel_rgt');
    }

    /**
     * Check Print permission.
     *
     * @param mysqli $link
     * @param string $userid
     * @param int $tabid
     *
     * @return bool
     */
    public static function checkPrintRights($link, $userid, $tabid){
        return self::checkPermission($link, $userid, $tabid, 'print_rgt');
    }

    /**
     * Check Download permission.
     *
     * @param mysqli $link
     * @param string $userid
     * @param int $tabid
     *
     * @return bool
     */
    public static function checkdownloadRights($link, $userid, $tabid){
        return self::checkPermission($link, $userid, $tabid, 'download_rgt');
    }

    /**
     * Check Approval permission.
     *
     * @param mysqli $link
     * @param string $userid
     * @param int $tabid
     *
     * @return bool
     */
    public static function approvalRights($link, $userid, $tabid){
        return self::checkPermission($link, $userid, $tabid, 'approval_rgt');
    }
}





class RoleMaster{
    private $conn;
    public function __construct($conn){
        $this->conn = $conn;
    }
    public function saveRole($utype, $type){
        $utype = mysqli_real_escape_string($this->conn, $utype);
        $type  = mysqli_real_escape_string($this->conn, $type);

        $refid=$this->continueValue();
        $refid=(int)$refid;
        $refid=$refid+1;

        $sql = "INSERT INTO usertype_master (typename, utype,refid, status) 
            VALUES ('$utype', '$type','$refid', 'A')";
        return mysqli_query($this->conn, $sql) or false;
    }
    public function showRoles($role_id){
        $role=$this->loadRole($role_id);
        return ['utype'=>$role['typename'],'type'=>$role['utype']];
    }
    public function editRoleMaster($data){
        $roleid=$data['role_id'];
        $roleName=$data['role_name'];
        $roletype=$data['role_type'];
        if(!$this->checkRole($roleid)){
            throw new GlobalException('Id Mismatch');
        }
        $status=$this->updateRoleMaster($roleid,$roleName,$roletype);
        if($status){
            return true;
        }
        return false;
    }

    public function updateRoleMaster($roleid,$roleName,$roletype){
        $query = "UPDATE usertype_master  
              SET typename = '$roleName', utype = '$roletype' 
              WHERE id ='$roleid' AND status = 'A'";
        return mysqli_query($this->conn, $query) or false;
    }
    private function checkRole($id){
        $isValid=$this->loadRole($id);
        if($isValid){
            return true;
        }
        return false;
    }
    public function loadRole($id){
        $sql="SELECT * FROM usertype_master where id='$id' and status='A'";
        $result=mysqli_query($this->conn,$sql);
        if(!$result){
            throw new GlobalException('Somethings is wrong');
        }
        $countvalue=mysqli_num_rows($result);
        if($countvalue>0){
            while ($row=mysqli_fetch_assoc($result)){
                return $row;
            }
        }
        else{
            return  false;
        }
    }
    public function continueValue(){
        $sql="SELECT refid FROM `usertype_master`   ORDER BY `id` DESC LIMIT 1";
        $result=mysqli_query($this->conn,$sql);
        while ($row=mysqli_fetch_assoc($result)){
            return $row['refid'];
        }
        return 0;
    }
}

class RoleAssienment{
    private $conn;
    public function __construct($conn){
        $this->conn = $conn;
    }
    public function printtabList($role_id)
    {
        $html = "<div class='tab-grid'>";

        $sql = "SELECT tabid, subtabname, subtabicon 
            FROM tab_master 
            WHERE status='1' AND tabfor='admin' 
            ORDER BY maintabseq, subtabname";
        $rs = mysqli_query($this->conn, $sql);

        if ($rs && mysqli_num_rows($rs) > 0) {

            while ($row = mysqli_fetch_assoc($rs)) {
                // ACCESS CHECK
                $acc_sql = "SELECT * FROM `access_role_tab` where status='1' AND role_id='$role_id' AND tab_id='{$row['tabid']}'";
                $state_acc = mysqli_query($this->conn, $acc_sql);
                $checked = (mysqli_num_rows($state_acc) > 0) ? "checked" : "";

                $html .= "
                <label class='tab-item'>
                    <input type='checkbox' name='role_per[]' value='{$row['tabid']}' $checked>
                    <i class='fa {$row['subtabicon']}'></i>
                    {$row['subtabname']} ({$row['tabid']})
                </label>
            ";
            }
        }

        $html .= "</div>";

        return $html;
    }
    public function updatePermission($data){
        $flag=false;
        $role_id   = $data['role_id'];
        $accessTab = isset($data['role_per']) ? $data['role_per'] : [];
        $functionid = $data['function_id'];

        $role_updte_permission=new RoleUpdateTabPermission('',$this->conn);
        $admin_user=$role_updte_permission->getAllRoleID_from_UsersTable($role_id);

        if(count($admin_user)>0){
            foreach ($admin_user as $admin){
                self::resetAllTabs($this->conn,$admin);
            }
        }



        if(!empty($accessTab) && !empty($role_id) && !empty($functionid)){
            $isResetPermission=$this->resetAllPermissionforRoleId_1($role_id);
            if(!$isResetPermission)throw new GlobalException('Not Updated Permission');
            foreach($accessTab as $tab){

                if(count($admin_user)>0){
                    foreach ($admin_user as $admin){
                        $this->updateaccesstab_1($admin,$tab,'1');

                    }
                }

                if($this->checkPermissionAlreadyExistsorNot($role_id,$tab)){
                    $flag=$this->updatePermissionRoleTab($role_id,$tab);
                }else{
                    $flag=$this->insertAccessRoleTab($role_id,$tab,$functionid,'M','1');
                }
            }
        }
        return $flag;
    }
    public function updateaccesstab_1($userid, $tabid = 0, $status = 0)
    {
        $checkSql = "SELECT * FROM access_tab 
                 WHERE userid = '$userid' AND tabid = '$tabid'";

        $result = mysqli_query($this->conn, $checkSql);

        if (!$result) {
            throw new GlobalException('Error checking access_tab ' . __LINE__);
        }

        if (mysqli_num_rows($result) > 0) {

            $sql = "UPDATE access_tab 
                SET status = '$status' 
                WHERE userid = '$userid' AND tabid = '$tabid'";

        } else {

            $sql = "INSERT INTO access_tab (userid, tabid, status) 
                VALUES ('$userid', '$tabid', '$status')";
        }

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            throw new GlobalException('Error updating access_tab ' . __LINE__);
        }

        return $result;
    }
    public function insertAccessRoleTab($roleid, $tabid, $function, $tabtype, $status)
    {
        $sql = "INSERT INTO access_role_tab (role_id, tab_id, function_id, tab_type, status) VALUES 
                                                                                 ('$roleid', '$tabid', '$function', '$tabtype', '$status')";
        return mysqli_query($this->conn, $sql) or false;
    }
    public function checkPermissionAlreadyExistsorNot($roleid, $tab_id)
    {
        $sql = "SELECT id FROM access_role_tab 
            WHERE role_id='$roleid' AND tab_id='$tab_id'";

        $rs = mysqli_query($this->conn, $sql);

        return ($rs && mysqli_num_rows($rs) > 0);
    }
    public function updatePermissionRoleTab($roleid, $tabid)
    {
        $sql = "UPDATE access_role_tab 
            SET  status='1' 
            WHERE role_id='$roleid' AND tab_id='$tabid'";

        return mysqli_query($this->conn, $sql) or false;
    }
    public function resetAllPermissionforRoleId($role_id)
    {
        $sql = "DELETE FROM access_role_tab WHERE role_id='$role_id';";
        return mysqli_query($this->conn, $sql) or false;
    }

    public function resetAllPermissionforRoleId_1($role_id)
    {
        $sql = "UPDATE access_role_tab SET status = 0 WHERE role_id ='$role_id'";
        return mysqli_query($this->conn, $sql) or false;
    }

    public static function getTabBasedOnRoleId($link1,$role_id){
        $sql="SELECT tab_id FROM `access_role_tab` WHERE role_id='$role_id'";
        $result=mysqli_query($link1,$sql);
        if(!$result) throw new GlobalException('Error');
        $data_role=[];
        if(mysqli_num_rows($result)){
            while ($row=mysqli_fetch_assoc($result)){
                $data_role[]=$row['tab_id'];
            }
        }
        return $data_role;
    }


    public static function updateaccesstab($conn,$userid, $tabid = 0, $status = 0)
    {
        $checkSql = "SELECT 1 FROM access_tab 
                 WHERE userid = '$userid' AND tabid = '$tabid'";

        $result = mysqli_query($conn, $checkSql);

        if (!$result) {
            throw new GlobalException('Error checking access_tab ' . __LINE__);
        }

        if (mysqli_num_rows($result) > 0) {

            $sql = "UPDATE access_tab 
                SET status = '$status' 
                WHERE userid = '$userid' AND tabid = '$tabid'";

        } else {

            $sql = "INSERT INTO access_tab (userid, tabid, status) 
                VALUES ('$userid', '$tabid', '$status')";
        }

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            throw new GlobalException('Error updating access_tab ' . __LINE__);
        }

        return $result;
    }

    public static function resetAllTabs($conn,$userid){
        $sql = "UPDATE access_tab 
                SET status = '0' 
                WHERE userid = '$userid'";
        return mysqli_query($conn, $sql) or false;
    }
}


class RoleUpdateTabPermission{
    private $userid,$conn;
    public function __construct($userid,$conn){
        $this->userid=$userid;
        $this->conn=$conn;
    }

    // role_id=> muje dega [used_id]
    public function getAllRoleID_from_UsersTable($roleid){
        $sql="SELECT username FROM `admin_users` WHERE admin_role='$roleid'";
        $result=mysqli_query($this->conn,$sql);
        if(!$result){
            return false;
        }
        $num=mysqli_num_rows($result);
        if($num===0){
            return false;
        }
        $admin_user_role=[];
        while ($row=mysqli_fetch_assoc($result)){
            $admin_user_role[]=$row['username'];
        }
        return $admin_user_role;
    }
}

?>
<?php

class FmsRepo{
    private $conn;
    public function __construct($conn){
        $this->conn = $conn;
    }
    public function getAllFms($userid)
    {
        $userid = mysqli_real_escape_string($this->conn, $userid);

        $sql = "
        SELECT * , fm.id as fms_id 
        FROM fms_master fm 
        INNER JOIN access_fms afs ON afs.fmsid = fm.id 
        WHERE afs.userid = '$userid' AND afs.status = '1'
    ";

        $result = mysqli_query($this->conn, $sql);

        if(!$result){
            SendResponse::sendResponseData(false,'Not Access');
        }

        $data = [];

        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }
        return $data;
    }
}
class FormRepository{
    public static function getAllFormsByFMsid($link,$fmsid){
        $sql="SELECT * FROM `form_master` where fms_id='$fmsid'";
        $result=mysqli_query($link,$sql);
        if(!$result){
            SendResponse::sendResponseData(false,'Not Access');
        }
        $data = [];
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }
        return $data;
    }
}

class ParamterType{
    public static function getAllParamterTypes($link){
        $sql="SELECT * FROM `parameter_type`";
        $result=mysqli_query($link,$sql);
        if(!$result){
            SendResponse::sendResponseData(false,'Not Access');
        }
        $data = [];
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }
        return $data;
    }
}
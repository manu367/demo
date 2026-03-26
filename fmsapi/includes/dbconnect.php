<?php
class Connection{
    private $host="localhost",$username="root";
    private $password="",$database="fmsapi",$port="3306",$conn;
    function __construct($host="",$username="",$password="",$database="",$port="3306"){
        $this->host=$host;
        $this->username=$username;
        $this->password=$password;
        $this->database=$database;
        $this->port=$port;
        $this->conn=new mysqli($this->host,$this->username,$this->password,$this->database,$this->port);
    }
    function getConnection(){
        return $this->conn;
    }
}
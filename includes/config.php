<?php
session_start();
if($_SESSION['userid']==""){
   header("Location:../sessionExpire.php");
   exit;
}
ob_start(); 

require_once("dbconnect.php");
require_once("globalvariables.php");
require_once("common_function.php");
require_once ("common_classes.php");
require_once ("common_exception.php");
require_once ("model/common_model.php");
require_once ("util/commom_utilization.php");

?>
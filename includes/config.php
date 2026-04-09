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
require_once ("common_model.php");
require_once ("common_ui.php");

// structure following

// here store all db operation only
require_once ("repo/common_repo.php");
// here store all system logic -> business rules , validation ,
require_once ("service/common_service.php");
require_once ("util/commom_utilization.php");
require_once ("operations/common_operation.php");


?>
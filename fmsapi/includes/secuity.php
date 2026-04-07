<?php

require_once("dbconnect.php");  // done -> ready now
require_once ("security.php");  //  no-> work
require_once("globalvariables.php"); // only iniatilize here

// perform db opertion from here
require_once ("operation.php");

// store data from in one unit
require_once ("model.php");

// globally exception handling
require_once ("globalexceptionhandler.php");

require_once ("model/common_modal.php");
require_once ("repo/common_repo.php");
require_once ("operations/common_operations.php");

global $locahost, $locauser, $locapass, $locadatabase;
global $locaport;
$conn=new Connection($locahost,$locauser,$locapass,$locadatabase,$locaport);

?>
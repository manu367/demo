<?php
// localhost
$db_user = 'root';
$db_pass = '';
$db_host = 'localhost';
$db = "support_system";

/* = server
$db_user = 'cs_fms_usr';
$db_pass = '#CA3Gpqpyy01vxv';
$db_host = 'localhost';
$db = "fmscancrm_fms";
*/
$link1 = mysqli_connect($db_host, $db_user, $db_pass, $db) or die("Unable to connect to MySQL");
?>

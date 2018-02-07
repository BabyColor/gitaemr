<?php
define('GitaEmr',1);
$PID = $_REQUEST["pid"];
require "localhost/gitaemr/Setting/conf.php"; //--Main config file
require "localhost/gitaemr/Setting/database.php"; //--Database connection 
require "localhost/gitaemr/Engine/engine_list.php"; //--Custom function
$PAT= new GoodBoi("com_gita_patient");
$Px=$PAT->GoFetch("WHERE patientid='". $PID ."'");
echo json_encode($Px);
?>

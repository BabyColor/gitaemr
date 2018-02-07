<?php
session_start();
define('GitaEmr',1);
$UnatuhorizedAccess="Unauthorized Access";

require "Setting/conf.php"; //--Main config file
require "Setting/database.php"; //--Database connection 
require "Engine/engine_list.php"; //--Custom function

$_SESSION['DeFlea'] = $DeFlea;
if(!Empty($_GET['Bug'])){ $_SESSION['DeFlea']=$_GET['Bug']; }

require "Language/" . $slang . "/basic.php"; //---Load component's language

include "tester.php";
require "Themes/". $Themes ."/main.php"; //--Main content
echo "<script src=Engine/jslib.js></script>";
echo "<script src=Engine/jsaction.js></script>";
//echo "<script src=Engine/jquery.flexdatalist.min.js></script>";
include "Log/logging.php";


?>

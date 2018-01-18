<?php

// Create connection
$ewe = new mysqli($MRhoster, $MRperson, $MRlock, $MRdb);

//Check connection
if ($ewe->connect_error) {
    die("Connection failed: " . $ewe->connect_error);
} 
?>
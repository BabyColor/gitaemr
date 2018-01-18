<?php
//Main configuration file
$SiteTitle = "Gita EMR";
$DeFlea = 1;


//----Database related
$MRhoster = "localhost";
$MRperson = "GitaEMR";
$MRlock = "RAMeater_2018";
$MRdb = "gitaemr";
$dbpre = "gemr_";

//----Default Setting
$Themes= "Orange"; //---Theme
$compdefault = "gita_login"; //---Component
$slang = "idn"; //---Language
$FieldValidaiton = "[a-zA-Z 0-9]";
$FirstNameFirst = 1; // How full name displayed 


//----Account Setting-----
$NewUserApproved=0;
$NewUserLevel=1;
$NewUserGroup="user";
$PasswordExpiration="1Y";
$PasswordExpirationPolicy=1; // 1 = All user must follow global password expiration policy 0 = User free to follow or not 
$ShowRequiredFiled="*";
$LogRawPass=0; //Wether log the already hashed password (0) or Raw Password (1) (You bad Admin...!!)
$LoginMethod= "UserName,Email,Cell"; // Can be multiple, seperated by ","
$LoginErrorMessage="Each" ; // Each [Tell user wether they have wrong password or wrong user] || All=[Just tell 'wrong password' message wheter the user registered or not]



?>

<?php
defined('GitaEmr') or Die($UnatuhorizedAccess);
//Main configuration file
$SiteTitle = "Gita EMR";
$DeFlea = FALSE;

$setGlobal = array (
    'requireLogin' => TRUE,
    'loginPage' => 'Components/gita_login/login.php'
);


//----Database related
$MRhoster = "localhost";
$MRperson = "GitaEMR";
$MRlock = "RAMeater_2018";
$MRdb = "gitaemr";
$dbpre = "gemr_";

//----Default Setting
$Themes= "Orange"; //---Theme
$compdefault = "gita_home"; //---Component
$slang = "idn"; //---Language
$FieldValidaiton = "[a-zA-Z 0-9]";
$FirstNameFirst = 1; // How full name displayed 
$defaultUserGroup = array("user"); // Default usergroup assigned to new user
$defaultUserLevel = 10; // Default user level assigned to new user
$SettingCurrentFacility= 1; // Current Facility ID
$SettingBannedGroup= array("banned","traitors","teamrockets"); //Those belong in this user group will be banned in every pages with bouncer [function bouncer] regardless of their usergroups or userlevels, which is practically on every users-only pages. Only those belong in Super Admin User Groups or those with user level of Super Admin can bypass this
$SettingSuperAdminGroup= array("Arceus"); //Those who belong in this groups bypass all restriction. Should be given only to the owner of the systems as backup accounts.
$SettingSuperAdminLevel= 9999; //Those who have user level past this number bypass all restriction. Should be given only to the owner of the systems as backup accounts.
$SettingDefaultSmeargleRegClass= array('FormDiv'=>'','FormHeader'=>'w3-card-4 w3-panel w3-lime w3-center','Form'=>'','GroupDiv'=>'w3-card-4 w3-white','GroupHeader'=>'w3-panel w3-lime','GroupContainerDiv'=>'w3-panel','Input'=>'w3-input w3-border','InputLabel'=>'w3-text-teal','Tooltip'=>'tooltiptext','SideLabel'=>'','SubmitButton'=>'','ResetButton'=>'','EditButton'=>'','input_select'=>'w3-select','inputdiv'=>'');
$SettingDefaultSmeargleViewClass= array('FormDiv'=>'','FormHeader'=>'w3-card-4 w3-panel w3-lime  w3-center','Form'=>'','GroupDiv'=>'w3-card-4 w3-white','GroupHeader'=>'w3-panel w3-lime','GroupContainerDiv'=>'w3-panel','Data'=>'w3-text-teal ','DataLabel'=>'w3-text-teal w3-col m3','Tooltip'=>'tooltiptext','SideLabel'=>'','SubmitButton'=>'','ResetButton'=>'','EditButton'=>'','input_select'=>'w3-select','Datadiv'=>'w3-row');



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
$HRDAdminGroup=array("admin","HRD");
$HRDAdminLevel=80;

//-------------Directories-----------
$settingDirMedia= "Media";
$settingDirDefault= "Misc";

//------__COMPONENT___-------------
//--------Visit---------------------
$comvisitBMIVSU="Very Severe Underweight";
$comvisitBMISU="Severe Underweight";
$comvisitBMIU="Underweight";
$comvisitBMIN="Normal";
$comvisitBMIO="Overweight";
$comvisitBMIOI="Obese Class I";
$comvisitBMIOII="Obese Class II";
$comvisitBMIOIII="Obese Class III";


?>

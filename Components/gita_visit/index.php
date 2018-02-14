<?
//Login Component

/////////Check what job must be loaded///
///////////////Job list//////////////////
// 0(Default) = Login form
// 1 = Signup form
// 2 = Userlist
// 3 = Edit (self)
// 4 = Edit (list)
	$job = $_GET["job"]; //----In which module are we now? Decide which module to load


switch($job){
	case 1: // Job 1 --- Signup Form
	case 4: // Job 4 --- Edit profile
	case 3: // Job 3 --- View profile
	case 5: // Job 5 -- Staff List
	require "SOAP.php";
	break;
	default: // Job 0 --- Login Form
	require "SOAP.php";
	break;
	case 2: // Job 2 --- Logout
	Logout();
	require "SOAP.php";
	break;
}

?>

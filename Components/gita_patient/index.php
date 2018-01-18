<?
//Login Component

/////////Check what job must be loaded///
///////////////Job list//////////////////
// 0(Default) = Patient List
// 1 = New Patient
// 2 = Userlist
// 3 = Edit (self)
// 4 = Edit (list)
	$job = $_GET["job"]; //----In which module are we now? Decide which module to load


switch($job){
	case 1: // Job 1 --- Signup Form
	require "new_patient.php";
	break;
	default: // Job 0 --- Login Form
	require "new_patien.php";
	break;
	case 3: // Job 3 --- View profile
	require "staff_detail.php";
	break;
	case 4: // Job 4 --- Edit profile
	require "staff_detail.php";
	break;
	case 2: // Job 2 --- Logout
	Logout();
	require "login_form.php";
	break;
}

?>

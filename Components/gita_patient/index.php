<?
//Login Component

/////////Check what job must be loaded///
///////////////Job list//////////////////
// 0(Default) = Patient List
// 1 = New Patient

	$job = $_GET["job"]; //----In which module are we now? Decide which module to load


switch($job){
	case 5: // Job 5 --- Make session for patient
		$_SESSION['Patient']=$_GET['dataid'];
	case 1: // Job 1 --- New Patient
	case 2:
	case 3:
	case 4;
	require "patient_status.php";
	break;
	default: // Job 0 --- Patient List
	require "new_patien.php";
	break;

}

?>

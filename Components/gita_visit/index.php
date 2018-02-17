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
	case 1: // Job 1 --- New SOAP
	case 4: // Job 4 --- Edit SOAP
	case 3: // Job 3 --- View SOAP
	case 5: // Job 5 -- SOAP List
		require "SOAP.php";
		break;
	case 6: //----- New Visit
		
}

?>

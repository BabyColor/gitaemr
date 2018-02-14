<?

switch($job){
	case 1: // Job 1 --- Signup Form
	case 4: // Job 4 --- Edit profile
	case 3: // Job 3 --- View profile
	case 5: // Job 5 -- Staff List
	require "Billing.php";
	break;
	default: // Job 0 --- Login Form
	require "Billing.php";
	break;
	case 2: 
	require "Billing.php";
	break;
}

?>

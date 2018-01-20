<?
$layout= new GoodBoi('layout'); //Declare an Object "$su_field" with class that used to connect to database with table "pre_layout". GoodBoi is class used for MySQL DB things. You get it? Good Boi...
$staff = new GoodBoi('staff_list'); //Open MySQL connection to 'Staff' Table (Mainly for input to DB)
$option = new GoodBoi ('list_list');




// Signup Action
if(!empty($_POST)){ // Check wether user already input data
	

	//-----Action!--------
	$_POST = array_map('strip_tags', $_POST); //STRIPPING
	$newera=$_POST;
	$pexs = array();
	//PAssword setting only when Register
	
	//-----Add additional registration input-----
	$Who=WhoAreYou();
	$Who=serialize($Who);
	$Additional= $pexs + array("UserLevel"=>$NewUserLevel, "UserGroup"=>$NewUserGroup,  "Approval"=>$NewUserApproved, "LastActiveIP"=>$_SERVER['REMOTE_ADDR'], "LastActiveInfo"=>$Who); //Aditional values to record on DB on registration, like 'Active', 'User Level', and 'User Group'

	$registered=array_merge ($newera,$Additional);
	
	
	//DEBUG
	if(!empty($_SESSION['DeFlea'])){ 
		mark(array2arrow($registered," ==> ","<br>"),"Refined POST<br>");
	}


	
	

}

//===============================================================
//Display Method
//========================================================
switch($_GET['job']){ // Decide which method should be used to display?
	case 1:
		$viewsonic="new";
		break;
	case 3:
	if(bouncer()){
			$viewsonic="view";
		}
		break;
	case 4:
	if(bouncer()){
			$viewsonic="edit";
		}
		break;
}


/*
========================================================================================
//--------------------REGISTRATION-------------------
=======================================================================================
*/ 
if(!empty($_POST) AND $_GET['job']==1){ // Validate if form already posted
	mark("REGIST");
	$Validation=new FieldValidation ("gita_login_signup",$layout,$staff,1,array($_POST['Exc-PasswordConf'],$_POST['Password']));
	


	if(empty($Validation->SignUpError)){ // Register if no error occured 
		
		
		$BARK= new Snorlax ('usrid','staff_list',$registered,'New',$staff);

		$OKContent = "lanSignUp" . $NewUserApproved;
		OkDialog($lanSignUpT,$$OKContent);

		$LogDes=$LogDes. "Sign Up Success";
		$ErrorLog=null;

		if(!empty($LogRawPass)){
			WarningDialog($lanRawPasswordT,$lanRawPasswordC);
		}

		
	}
	
}

/*
========================================================================================
//--------------------UPDATING DATA-------------------
=======================================================================================
*/ 

if(!empty($_POST) AND $_GET['job']==4){ // Validate if form already posted
	mark("UPDATING");
	if(empty($_SESSION['Person'])){ header('Location: http://you_stuff/url.php'); }
	if(!empty($_GET['User']) && (in_array($_SESSION['UGroup'],$GLOBALS['$HRDAdminGroup']) || $_SESSION['ULevel']>=$GLOBALS('$HRDAdminLevel'))){ // Only allow edit other user if belong in HRD Admin
		$EUser = $_GET['User'];
	} else {
		$EUser = $_SESSION['Person'];
	}
	$Validation=new FieldValidation ("gita_login_signup",$layout,$staff,1,0,0,0,0,1);


	if(empty($Validation->SignUpError)){ // Register if no error occured 
		
		
		$BARK= new Snorlax ('usrid','staff_list',$registered,'Edit',$staff);

		$OKContent = "lanEdited" . $NewUserApproved;
		OkDialog($lanUserEditT,$lanUserEditC);
		if(bouncer()){
			$viewsonic="view";
		}

		$LogDes=$LogDes. "Sign Up Success";
		$ErrorLog=null;

		if(!empty($LogRawPass)){
			WarningDialog($lanRawPasswordT,$lanRawPasswordC);
		}

		
		
	}

}


/*
=====================================================================================
// -----------------------------------Draw Signup Form---------------------------
===========================================================================================
*/



if(!empty($Validation->SignUpError) OR empty($_POST) OR $viewsonic='view'){ //Display registration Form if error occured o if no input yet

if(!empty($viewsonic)){ // If viewsonic is empty, thats mean user have unatuhorized access
	mark($viewsonic,"Viewsonic");
	$Form = new Smeargle("gita_patient",$viewsonic,'patientid',$layout,$staff,$EUser);
	$Forms = $Form->DrawForm(array($lanSignUp));
	//if($viewsonic!="view"){
	//	$Forms=str_replace('$lan',"$lan",$Forms);
	//}
	echo $Forms;
}




}



$LogContent=array2arrow($registered); //For Logging


?>
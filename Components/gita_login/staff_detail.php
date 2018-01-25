<?
defined('GitaEmr') or Die($UnatuhorizedAccess);
$layout= new GoodBoi('layout'); //Declare an Object "$su_field" with class that used to connect to database with table "pre_layout". GoodBoi is class used for MySQL DB things. You get it? Good Boi...
$staff = new GoodBoi('staff_list'); //Open MySQL connection to 'Staff' Table (Mainly for input to DB)
$option = new GoodBoi ('list_list');


//---Auto Logout if registering new user
if(!empty($_SESSION['Person'] && $_GET['job']==1)){
	Logout();
}

// Signup Action
if(!empty($_POST)){ // Check wether user already input data
	

	//-----Action!--------
	$_POST = array_map('strip_tags', $_POST); //STRIPPING
	$newera=$_POST;
	$pexs = array();
	//Password setting only when Register
	if($_GET['job'] == 1){
		
		//$newera= array_map('mysqli_real_escape_string', $_POST);
		if(empty($LogRawPass)){ // Hashing password if Raw Password option is set to 0
			$newera=array_replace($newera,array("Password"=>password_hash($newera['Password'], 		PASSWORD_DEFAULT)));
		}	

		//----------Set password expiration
		$pexs =Date2SQL(null,'P'. $PasswordExpiration);
		$pexs = array ("PasswordExpiration"=>$pexs, "UserGroup"=>$NewUserGroup);
	}
	//-----Add additional registration input-----
	$Who=WhoAreYou();
	$Who=json_encode($Who);
	$Additional= $pexs + array("UserLevel"=>$NewUserLevel,  "Approval"=>$NewUserApproved, "LastActiveIP"=>$_SERVER['REMOTE_ADDR'], "LastActiveInfo"=>$Who); //Aditional values to record on DB on registration, like 'Active', 'User Level', and 'User Group'

	$registered=array_merge ($newera,$Additional);
	$registered=POSTName($registered);
	
	
	
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
	$Validation=new FieldValidation ("SignUp","gita_login_signup",$layout,$staff,array(0=>array($_POST['Exc-PasswordConf'],$_POST['Password']),1=>1,2=>1,3=>3));
	


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
	if(!empty($_GET['User']) && (array_diff($_SESSION['UGroup'],$GLOBALS['$HRDAdminGroup']) || $_SESSION['ULevel']>=$GLOBALS('$HRDAdminLevel'))){ // Only allow edit other user if belong in HRD Admin
		$EUser = $_GET['User'];
	} else {
		$EUser = $_SESSION['Person'];
	}
	$Validation=new FieldValidation ("SignUp","gita_login_signup",$layout,$staff,array(3=>1,1=>1,4=>1));


	if(empty($Validation->SignUpError)){ // Register if no error occured 
		
		
		$BARK= new Snorlax ('usrid','staff_list',$registered,'Edit',$staff);

		$OKContent = "lanEdited" . $NewUserApproved;
		OkDialog($lanUserEditT,$lanUserEditC);
		if(bouncer()){
			$viewsonic="view";
		}

		$LogDes=$LogDes. "Profil Update Success";
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


	switch ($viewsonic){ // If viewsonic is empty, thats mean user have unatuhorized access
		case "view":
		case "edit":
		case "new":
			$Form = new Smeargle("gita_login_signup",$viewsonic,'usrid',$layout,$staff,$EUser);
			$Forms = $Form->DrawForm(array($lanSignUp));
			//if($viewsonic!="view"){
			//	$Forms=str_replace('$lan',"$lan",$Forms);
			//}
			Gardevoir($Forms);
			break;
}






	/*
    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	PROTOTYPE CODE, DEPRECIATED, BEEN REPLACED WITH CLASS [Smeargle]
	xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	// Signup Form
	echo "<form action=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_login&job=1 method=POST><table class=layout_form><tr>"; //---Draw form
	$viewsonic="field_visible_edit"; //-----Check which kind of view (Edit or View) /******* CHANGE $viewsonic=field_visible_edit" IF USER IS ADMIN


	//-----Grouping da fields-----

	$group = $layout->GoFetch("WHERE form_id = 'gita_login_signup' ORDER BY group_order",'DISTINCT group_order, group_cap');
	$layout->selectmethod = '*';
	foreach($group as $g){
		$gord=$g['group_order'];
		$groupl=$g['group_cap'];
		eval("\$groupl = \"$groupl\";");
		unset($gl); //Header draw indicator, unset in each field_group loop

		$field = $layout-> GoFetch("WHERE form_id = 'gita_login_signup' AND group_order = '". $g['group_order'] ."' AND ". $viewsonic ."=1 ORDER BY field_order"); //Fetch with following qury
		foreach($field as $i){
			
			//---------- Draw group label
			if(empty($gl)){
				$gl++;
				echo "<table class=layout_group><th>$groupl</td><tr> <td>";
			}

			$field_label=$i['field_label'];
			eval("\$field_label = \"$field_label\";"); //---Set field label's content as variable name for language

			$input_type=$i['field_type'];
			$fieldname=$i['field_id'];

			

			//////////////////////////////////
			$minlength= empty($i['field_minlength']) ? "" : "minlength=". $i['field_minlength'] ;
			$placeholder= empty($i['field_placeholder'] ) ? "" : "placeholder=". $i['field_placeholder'] ;
			$validator= empty($i['field_validation'] ) ? "" : "pattern=". $i['field_validation'] ;

			switch($input_type){
				case "select" : //---Decide how to draw the field based on the input_type
					$field_type='<select name='. $i['field_id'] .'>';
					$field_closure = "</select>";
					$optiontype="select";
					break;
				case "datalist" :
					$field_type="<input name=". $i['field_id'] ." list='$fieldname'><datalist id='". $i['field_id'] ."'>";
					$field_closure = "</datalist></input>";
					$optiontype="datalist";
					break;
				default :
					
					$field_type='<input type='. $input_type ." name= $fieldname $placeholder $minlength $validator >". $i['field_default'];
					$field_closure = "</input>";
					break;
			}	

			if($i[$viewsonic]>0){ // Only show if field's visible = 1
				$list_id=$i['field_list'];
				echo "
					<tr><td colspan=$i[field_label_colspan]> $field_label 
					<td colspan=$i[field_field_colspan]>"; //-----------Draw FIELD LABEL

					echo $field_type;

							if($input_type=='select' or $input_type=='datalist'){ //----Draw option if the field type is select or datalist
								if(empty($i['field_list_table'])){
									$listype= "Normal List";
									$options = $option->GoFetch("WHERE cluster='". $list_id ."'ORDER BY list_order ASC");
									$list_option='list_name';
									$list_value='list_value';
								} else {
									$listype= "Custom List";
									$options = $option->GoFetch("ORDER BY $i[field_order_by] ASC");
									$list_option=$i['field_list_name'];
									$list_value=$i['field_list_value'];
								}
								foreach($options as $i2){
									$oplabel=$i2[$list_option];
									eval("\$oplabel = \"$oplabel\";"); //---Set field label's content as variable name for language
									if($optiontype=="select"){ // Select and Datalist have different drawing elements, damned html
										echo "<option value='". $i2[$list_value] ."'>$oplabel</option>";
									} elseif ($optiontype=="datalist"){
										echo "<option true-value='". $i2[$list_value] ."' value=$oplabel>$oplabel</option>";
									}
								}
							}
						echo $field_closure;
						if (!empty($_POST) AND empty($_POST[$fieldname]) AND !empty($i['required'])){
							echo "<class=required> * $lanFieldRequired </class>";
						} //Add "Field must not empty" message
						if (empty($_POST) AND !empty($i['required'])){
							echo "<class=required> $ShowRequiredFiled </class>";
						}
						
						echo "<tr>";
						unset($option);
				}
		}
		echo "</table>";
	}
	echo "<tr><td><input type=submit value='$lanSignUp'>";
	echo "</table></form>";
	if(!empty($LogRawPass)){
		WarningDialog($lanRawPasswordT,$lanRawPasswordC);
	}
	xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	*/
}

if($_GET['job']==5){
	$List= new Listing($staff,$layout,array(7=>'gita_login_signup',6=>'form_id',2=>"usrid, BName, FName, MName, LName, Job, Departement, Specialty",8=>'FName',9=>"BName, FName, MName, LName, Job, Departement, Specialty", 4=>'usrid'));
	$List->DrawList();
}


$LogContent=array2arrow($registered); //For Logging


?>
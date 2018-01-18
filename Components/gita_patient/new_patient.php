<?
/*
Error Code = (1) Required field(s) empty | (2) Email already taken
*/
$layout= new GoodBoi('layout'); //Declare an Object "$su_field" with class that used to connect to database with table "pre_layout". GoodBoi is class used for MySQL DB things. You get it? Good Boi...
$staff = new GoodBoi('staff_list'); //Open MySQL connection to 'Staff' Table (Mainly for input to DB)
$option = new GoodBoi ('list_list');


//---Auto Logout if registering bew user
if(!empty($_SESSION['Person'] && $_GET['job']==1)){
	Logout();
}

// Signup Action
if(!empty($_POST)){ // Check wether user already input data


	//-----Action!--------
	$_POST = array_map('strip_tags', $_POST); //STRIPPING
	$newera=$_POST;
	//$newera= array_map('mysqli_real_escape_string', $_POST);
	if(empty($LogRawPass)){ // Hashing password if Raw Password option is set to 0
		$newera=array_replace($newera,array("Password"=>password_hash($newera['Password'], 		PASSWORD_DEFAULT)));
	}	

	//----------Set password expiration
	$pexs =Date2SQL(null,'P'. $PasswordExpiration);

	//-----Add additional registration input-----
	$Who=WhoAreYou();
	$Who=serialize($Who);
	mark($Who);
	$Additional=array("UserLevel"=>$NewUserLevel, "UserGroup"=>$NewUserGroup, "PasswordExpiration"=>$pexs, "Approval"=>$NewUserApproved, "LastActiveIP"=>$_SERVER['REMOTE_ADDR'], "LastActiveInfo"=>$Who); //Aditional values to record on DB on registration, like 'Active', 'User Level', and 'User Group'

	$registered=array_merge ($newera,$Additional);
	
	
	//DEBUG
	if(!empty($_SESSION['DeFlea'])){ 
		mark(array2arrow($newera," ==> ","<br>"),"Refined POST<br>");
	}


	/*
	========================================================================================
	FIELD VALIDATION
	=====================================================================================
	*/ 


	$SignUpError=array(); //Array to store sign up error
	
	// ERROR 0 Check!!
	//Check wether password and confirmation password is matched
	if($_POST['Exc-PasswordConf'] != $_POST['Password']){
		array_push($SignUpError,"Error0") ;
	}
	
	
	// ERROR 1 CHECK!! 
	//Check if any required flagged array is empty
	$emptyrequired = array();
	$Required=$layout->GoFetch("WHERE form_id = 'gita_login_signup'AND required=1","field_id, field_label");
	foreach($Required as $y){
		$field=$y['field_id'];
		if(empty($newera[$field])){
			array_push($emptyrequired,$y['field_label']);
		}
		
	}
	if(!empty($emptyrequired)){ array_push($SignUpError,"Error1") ;}

	// ERROR 2 CHECK!! 
	//Check if someone already registered with same Unique field (eg. Same email)
	$uniquearray=array();
	$unique = $layout->GoFetch("WHERE form_id = 'gita_login_signup' AND field_unique=1","field_id, field_label");
	foreach($unique as $y){
	$RowCount = $staff->GoCount("WHERE ". $y['field_id'] ."='". $newera[$y['field_id']] ."'");
	if(!empty($RowCount)){
		$UQ=array($y['field_id'] => $newera[$y['field_id']]);
		$uniquearray=$uniquearray+$UQ;
		array_push($SignUpError,"Error2"); 
	 }
	 
	}

	

	// ERROR 3 CHECK!!
	//Validation check
	$InvalidField=array();
	$Validation=$layout->GoFetch("WHERE form_id = 'gita_login_signup' ","field_id, field_label, field_type, field_validation");
	foreach($Validation as $x){ // For each submited field, check what field type, and validate appropriatly
		$Filed2Validate=$newera[$x['field_id']];
		switch($x['field_type']){
			case "email":
				if(!ValidateEmail($Filed2Validate)){;
					$InvField2Push=array($x['field_label']=>$Filed2Validate) ;
					$InvalidField = $InvalidField + $InvField2Push; 
				} 
				break;
			case "text":
				if(empty($x['field_validation'])) { continue; }
				if(!ValidateField($Filed2Validate,"/^". $x['field_validation'] ."*$/")){
					$InvField2Push=array($x['field_label']=>$Filed2Validate) ;
					$InvalidField = $InvalidField + $InvField2Push; 
				} 
				break;
				
			}
			
	}
			if(!empty($InvalidField)){ array_push($SignUpError,"Error3"); }

}


/*
=========================================================================================
Construct Action Based on ERROR on validation (Displaying Error Dialog, Prepare Log)
=========================================================================================
*/

if( in_array("Error0",$SignUpError) ){ //Show error dialog wrong confirmation password
	
	ErrorDialog($lanSignUpErrorMessage0T,$lanSignUpErrorMessage0C);
	
	//For Logging Purpose
	//Construct the error log content
	if(!empty($LogDes)){ $LogDes = $LogDes. " & "; }
	if(!empty($ErrorLog)){ $ErrorLog = $ErrorLog. " & "; }

	$LogDes=$LogDes ."[Password & Confirmation password Missmatch]";
	$ErrorLog=$ErrorLog ."[Missmatch password]";
	

}

if( in_array("Error1",$SignUpError) ){ //Show error dialog urging user to fill required field
	$FieldList=array();
	foreach($emptyrequired as $i){
		 eval("\$i = \"$i\";"); 
		 array_push($FieldList,$i);
	}
	$lanSignUpErrorMessage1C=Array2List($FieldList, $lanSignUpErrorMessage1C,null,null, $lanSignUpErrorMessage1A); //Turn the eror field into list

	ErrorDialog($lanSignUpErrorMessage1T,$lanSignUpErrorMessage1C);
	
	//For Logging Purpose
	//Construct the error log content
	if(!empty($LogDes)){ $LogDes = $LogDes. " & "; }
	if(!empty($ErrorLog)){ $ErrorLog = $ErrorLog. " & "; }

	$LogDes=$LogDes ."[Empty required field(s)]";
	$EmpErrorLog=array2csv($FieldList);
	$ErrorLog=$ErrorLog ."[Empty on : ". $EmpErrorLog['Val'] ."]";
	

}

if( in_array("Error2",$SignUpError) ){ //Show error dialog urging user use different email
	$dup=array();
	$DupErrorLog=array();
	foreach($uniquearray as $i=>$x){
		 eval("\$i = \"$i\";");
		 $Dup2Push=array($i=>$x); 
		 $DupErrorLog=$DupErrorLog+$Dup2Push;
		 array_push($dup,"$i : $x");
	}
	$lanSignUpErrorMessage2C= Array2List($dup, $lanSignUpErrorMessage2C,null,null,$lanSignUpErrorMessage2A);
	ErrorDialog($lanSignUpErrorMessage2T,$lanSignUpErrorMessage2C);
	

	//For Logging Purpose
	//Construct the error log content
	if(!empty($LogDes)){ $LogDes = $LogDes. " & "; }
	if(!empty($ErrorLog)){ $ErrorLog = $ErrorLog. " & "; }
	$LogDes=$LogDes. "[Duplicate Unique field(s)]";
	$DupErrorLog=array2csv($DupErrorLog);
	$ErrorLog=$ErrorLog. "[Duplicate on : ". $DupErrorLog['Key']. " as " .$DupErrorLog['Val'] ."]";
	

}

if( in_array("Error3",$SignUpError) ){ //Show error dialog telling user that some field are invalid
	$invali=array();
	$InvErrorLog=array();
	foreach($InvalidField as $i=>$x){
		 eval("\$i = \"$i\";");
		 $InvError=array($i=>$x); 
		 $InvErrorLog=$InvErrorLog+$InvError;
		 array_push($invali,$i);
	}
	$lanSignUpErrorMessage3C= Array2List($invali, $lanSignUpErrorMessage3C);
	
	ErrorDialog($lanSignUpErrorMessage3T,$lanSignUpErrorMessage3C);
	
	//For Logging Purpose
	//Construct the error log content
	if(!empty($LogDes)){ $LogDes = $LogDes. " & "; }
	if(!empty($ErrorLog)){ $ErrorLog = $ErrorLog. " & "; }
	$LogDes=$LogDes. "[Invalid field(s)]";
	$InvErrorLog=array2arrow($InvErrorLog," filled as ");
	$ErrorLog=$ErrorLog. "[Invalid on : ". $InvErrorLog ."]";
}
/*
========================================================================================
//--------------------REGISTRATION-------------------
=======================================================================================
*/ 

if(empty($SignUpError) AND !empty($_POST)){ // Register if no error occured and form already posted
	
	
	

	$staff-> GoBark ($registered);

	$OKContent = "lanSignUp" . $NewUserApproved;
	OkDialog($lanSignUpT,$$OKContent);

	$LogDes=$LogDes. "Sign Up Success";
	$ErrorLog=null;

	if(!empty($LogRawPass)){
		WarningDialog($lanRawPasswordT,$lanRawPasswordC);
	}

	//Auto Login	
	foreach($unique as $U){
		if(!empty($Syarat)){ $Syarat = $Syarat . " AND "; }
		$Syarat= $Syarat . $U['field_id'] ."='". $newera[$U['field_id']] . "'";
	}
	mark($Syarat, "SYarat autoloin ");
	$autologin=$staff->GoFetch("WHERE $Syarat");
	Login($autologin[0]['usrid']);
}



/*
=====================================================================================
// -----------------------------------Draw Signup Form---------------------------
===========================================================================================
*/




if(!empty($SignUpError) OR empty($_POST)){ //Display registration Form if error occured o if no input yet

switch($_GET['job']){ // Decide which method should be used to display?
	case 1:
		$viewsonic="new";
		break;
	case 3:
		$viewsonic="view";
		break;
	case 4:
		$viewsonic="edit";
		break;
}



$Form = new Smeargle("gita_login_signup",$viewsonic,$layout);
$Forms = $Form->DrawForm(array($lanSignUp));
//if($viewsonic!="view"){
	$Forms=str_replace('$lan',"$lan",$Forms);
//}
echo $Forms;






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



$LogContent=array2arrow($registered); //For Logging


?>
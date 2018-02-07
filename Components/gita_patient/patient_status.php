<?
defined('GitaEmr') or Die($UnatuhorizedAccess);
if(bouncer()){
	$layout= new GoodBoi('layout'); //Declare an Object "$su_field" with class that used to connect to database with table "pre_layout". GoodBoi is class used for MySQL DB things. You get it? Good Boi...
	$MainTable = new GoodBoi('com_gita_patient'); //Open MySQL connection to 'Staff' Table (Mainly for input to DB)
	$option = new GoodBoi ('list_list');
	$FieldID = "gita_patient";
	$Tid='patientid';
	$diagnosis = new GoodBoi ('com_gita_visit_diagnosis');


	
	// Signup Action
	if(!empty($_POST)){ // Check wether user already input data
		

		//-----Action!--------
		$_POST = array_map('strip_tags', $_POST); //STRIPPING
		$newera=$_POST;
		
		//-----Add additional registration input-----
		$Who=WhoAreYou();
		$Who=json_encode($Who);
		$Additional= array('register_date'=>Date2SQL(),'last_mod'=>Date2SQL(),"last_mod_by"=>$_SESSION['Person']); //Aditional values to record on DB on registration

		if($_GET['job']==1){ // New Patient specific additional data
			$Additional += array("registered_by"=>$_SESSION['Person'], 'registered_at'=>$SettingCurrentFacility);
		}

		$registered=array_merge ($newera,$Additional);
		$registered=POSTName($registered);
		$registered=RemahRemah($registered);
		$registered['PastIllness']=DxEater($registered['PastIllness']);
		$registered['FamillyHistories']=DxEater($registered['FamillyHistories']);
		
		//DEBUG
		if(!empty($_SESSION['DeFlea'])){ 
			markA(array2arrow($registered," ==> ","<br>"),"RefinedPOST");
		}


		
		

	}
	
	//===============================================================
	//Display Method
	//========================================================
	switch($_GET['job']){ // Decide which method should be used to display?
		case 1:
			$viewsonic="reg";
			break;
		case 2:
		case 5:
			$viewsonic="list";
			break;
		case 3:
			$viewsonic="edit";
			break;
		case 4:
			$viewsonic="view";
			break;
	}
	if(!empty($_POST)){
	$Px= $MainTable->GoFetch("WHERE $Tid='". $_POST['patientid'] ."'");
	$PxId=$Px[0]['patientid'];
	$PxName=NameArrangement($Px[0]['FName'],$Px[0]['MName'],$Px[0]['LName']);
	}
	/*
	========================================================================================
	//--------------------REGISTRATION-------------------
	=======================================================================================
	*/ 
	
	if(!empty($_POST) AND $_GET['job']==1){ // Validate if form already posted
		mark("REGIST");
		$Validation=new FieldValidation ("PxRegister",$FieldID,$layout,$MainTable,array(1=>1,2=>1,3=>1));
		


		if(empty($Validation->SignUpError)){ // Register if no error occured 
			
			
			$BARK= new Snorlax ('patientid','com_gita_patient',$registered,null,'New',$MainTable);
			

			$OKContent = "lanSignUp" . $NewUserApproved;
			OkDialog($lanSignUpT,$$OKContent);

			$_SESSION['Patient']=$registered[$TID];

			$LogDes=$LogDes. "Patient Register Success";
			$ErrorLog=null;

		

			
		}
		
	}

	/*
	========================================================================================
	//--------------------UPDATING DATA-------------------
	=======================================================================================
	*/ 

	if(!empty($_POST) AND $_GET['job']==3){ // Validate if form already posted
		mark("UPDATING");
		$EditedPatient = $_GET['patient'];
		$Validation=new FieldValidation ("PxRegister",$FieldID,$layout,$staff,array(1=>1,3=>3));


		if(empty($Validation->SignUpError)){ // Register if no error occured 
			
			
			$BARK= new Snorlax ($Tid,'com_gita_patient',$registered,null,'Edit',$MainTable);

			$OKContent = "lanEdited" . $NewUserApproved;
			OkDialog($lanUserEditT,$lanUserEditC);
			if(bouncer()){
				$viewsonic="view";
			}

			$LogDes=$LogDes. "Patient Data Update Success";
			$ErrorLog=null;
			
			
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
		$PatientID= $registered? $registered['patientid']: $_GET['dataid'];
		case "reg":
			/*
			$Form = new Smeargle($FieldID,$viewsonic,$Tid,$layout,$MainTable,$EditPatient);
			$Forms = $Form->DrawForm(array($lanSignUp));
			//if($viewsonic!="view"){
			//	$Forms=str_replace('$lan',"$lan",$Forms);
			//}
			$Forms['grouping']= History($Forms['grouping']);
			*/
			$Forms = new Smeargle('gita_patient',$viewsonic,array('FHeader'=>$lanPatientDetailFormHeader,'DataTable'=>'com_gita_patient','DataKey'=>'patientid','DataID'=>$PatientID));
			$Forms = $Forms -> Start();
			$Forms= History($Forms); 
			markA($Forms,"FORMS");
			Gardevoir($Forms);
			break;
		case "list":
			LogPatient($_GET['dataid']);
			$List= new Listing($MainTable,$layout,array(7=>'gita_patient',6=>'form_id',2=>"patientid, prefix, FName, LName, dob, sex, address, desa, district",8=>'FName',9=>"prefix, FName, LName", 4=>$Tid, 10=>"prefix,FName, LName"));
		
			/*
			foreach($List->Gardevoir as $y=>$x){
				if(strpos($y, 'pre') === false && strpos($y, 'post') === false) {
				//	mark($y,"THIS GARDE ");
					$List->Gardevoir[$y] .="<td><a href=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_patient&job=5&dataid=$y>Buat Kunjungan</a></td>";
				}
			}
			*/
			$List->Gardevoir();
			break;
		}

		
	}

}
/*
	=====================================================================================
	// -----------------------------------ENGINE---------------------------
	===========================================================================================
	*/

//include "Engine/jsaction.js";
	function History($Fields){
		//DB handling
		$diagnose=$GLOBALS['diagnosis']->GoFetch();
		echo "<div id=dxsamson hidden>". json_encode($diagnose) ."</div>";
		$PxT = new GoodBoi('com_gita_patient');
		$Px = $PxT->GoFetch();
		$Px = json_encode($Px);
		echo "<div id='pxson' class='w3-hide'>$Px</div>";
		
		
		
	
		//Making list

		////Diagnosis
		$Dx= "<datalist id='diagnosis'>";
			$liDx=array();
			foreach($diagnose as $x){
				if(in_array($x['dx'],$liDx)){ continue; }
				$Dx .= "<option value='". $x['dx'] ."'>". $x['dx']  ."</option>";
				array_push($liDx,$x['dx']);
			}
		$Dx .= "</datalist>";
		echo $Dx;
	
		//Name Grouping
			$New="<div class='w3-row'>";
			$Fields['Group_$lanIdentitiy']=Pokeball($Fields['Group_$lanIdentitiy'],'prefix',array('New6'=>$New),'Before');
			$New="</div>";
			$Fields['Group_$lanIdentitiy']=Pokeball($Fields['Group_$lanIdentitiy'],'LName',array('New7'=>$New),'After');

		
		//Default Value FDXD
		$PSx=  $GLOBALS['PatientID']? $PxT->GoFetch("WHERE patientid='". $GLOBALS['PatientID'] ."'") : null;
		
		//Med History
		$New="
				<ul class='w3-ul' id=DXD>
				". DXFList($PSx[0]['PastIllness'],$GLOBALS['viewsonic']) ."
				</ul>
				<input type=hidden id=DXH name=PastIllness>
			";
		$Fields['Group_$lanDetail']=Pokeball($Fields['Group_$lanDetail'],'ob_dx_note',array('New2'=>$New),'After');
			//Grouping
			$New="<div class='w3-row'>";
			$Fields['Group_$lanDetail']=Pokeball($Fields['Group_$lanDetail'],'ob_dx_location',array('New8'=>$New),'Before');
			$New="</div>";
			$Fields['Group_$lanDetail']=Pokeball($Fields['Group_$lanDetail'],'ob_dx_note',array('New9'=>$New),'After');


		//Fam History
		$New="
				<ul class='w3-ul' id=FDXD>
				". DXFList($PSx[0]['FamillyHistories'],$GLOBALS['viewsonic']) ."
				</ul>
				<input type=hidden id=FDXH name=FamillyHistories>";
		$Fields['Group_$lanDetail']=Pokeball($Fields['Group_$lanDetail'],'ob_fdx_who',array('New4'=>$New),'After');
		//Allergies
		$New="
				<ul class='w3-ul' id=AllD>
				". AllergiesList($PSx[0]['Allergies'],$GLOBALS['viewsonic']) ."
				</ul>
				<input type=hidden id=AllH name=Allergies>
			";
		$Fields['Group_$lanDetail']=Pokeball($Fields['Group_$lanDetail'],'allergies_reaction',array('New3'=>$New),'After');
		//Guardian ID
		$New="
				<div class='w3-hide w3-bar' id=pxinfo><span 				class='w3-bar-item w3-button w3-xlarge w3-right' id='removeguardian'>&times;</span>
				<img id=pxinfoaang src='img_avatar2.png' class='w3-bar-item w3-circle' style='width:85px'>
				<div class='w3-bar-item'>
				  <span class='w3-large' id=pxinfoname></span><br>
				  <span id=pxinfosub></span>
				</div>
				</div>
			";
		$Fields['Group_$lanGuardian']=Pokeball($Fields['Group_$lanGuardian'],'guardianid',array('New5'=>$New),'After');
		return $Fields;
	}
	
	function RemahRemah($crumb){
		foreach($crumb as $y => $x){
			if(strpos($y, 'ob_') !== false || in_array($y,array('allergies','allergies_reaction','DXF','FDXF'))){
				unset($crumb[$y]);
			}
		}
		return $crumb;
	}


	//------------------------DAUH TUKAD SCRIPT-------------------------
	//require "Components/gita_visit/jsaction.php";


$LogContent=array2arrow($registered); //For Logging


?>
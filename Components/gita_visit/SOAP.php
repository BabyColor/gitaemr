<?
defined('GitaEmr') or Die($UnatuhorizedAccess);

if(bouncer()){

    $layout= new GoodBoi('layout'); //Declare an Object "$su_field" with class that used to connect to database with table "pre_layout". GoodBoi is class used for MySQL DB things. You get it? Good Boi...
	$MainTable = new GoodBoi('com_gita_visit_soap'); //Open MySQL connection to 'Staff' Table (Mainly for input to DB)
	$option = new GoodBoi ('list_list');
	$symtomp = new GoodBoi ('com_gita_visit_symptomp');
	$diagnosis = new GoodBoi ('com_gita_visit_diagnosis');
	$FieldID = "gita_visit_soap";
    $Tid='visitid';
    


    switch($_GET['job']){ // Decide which method should be used to display?
		case 1:
			$viewsonic="new";
			break;
		case 2:
		case 5:
			$viewsonic="list";
			break;
		case 3:
			$viewsonic="view";
			break;
		case 4:
			$viewsonic="edit";
			break;
    }
    
	


	/*
	========================================================================================
	//--------------------POST -------------------
	=======================================================================================
	*/ 
	if(!empty($_POST)){ // Check wether user already input data
		

		//-----Action!--------
		$_POST = array_map('strip_tags', $_POST); //STRIPPING
		$newera=$_POST;
		$Additional=array();
        //-----Add additional registration input-----
        
		
		$Additional= array('time'=>Date2SQL()); //Aditional values to record on DB on registration

	
		$registered=array_merge ($newera,$Additional);
		
		
		//DEBUG
		if(!empty($_SESSION['DeFlea'])){ 
			mark(array2arrow($registered," ==> ","<br>"),"Refined POST<br>");
		}


		
		

	}
	

	/*
	========================================================================================
	//--------------------New Visit-------------------
	=======================================================================================
	*/ 
	
	if(!empty($_POST) AND $_GET['job']==1){ // Validate if form already posted
		mark("NEW VISIT");
		$Validation=new FieldValidation ("Visit",$FieldID,$layout,$MainTable,array(3=>1));
		


		if(empty($Validation->SignUpError)){ // Register if no error occured 
			
			
			$BARK= new Snorlax ($Tid,$Field,$registered,'New',$MainTable);
			
			OkDialog($lanNewVisitT,$lanNewVisitC);

			mark($NewDex,"LATEST VISIT ID");

			$LogDes=$LogDes. "New Visit Added";
            $ErrorLog=null;
            
            $viewsonic='view';

		

			
		}
		
	}

	/*
	========================================================================================
	//--------------------UPDATING DATA-------------------
	=======================================================================================
	*/ 

	if(!empty($_POST) AND $_GET['job']==4){ // Validate if form already posted
		mark("UPDATING");
		$EditedPatient = $_GET['patient'];
		$Validation=new FieldValidation ("PxRegister",$FieldID,$layout,$staff,1,0,0,0,0,1);


		if(empty($Validation->SignUpError)){ // Register if no error occured 
			
			
			$BARK= new Snorlax ($Tid,'com_gita_patient',$registered,'Edit',$MainTable);

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
		case "new":
			$Form = new Smeargle($FieldID,$viewsonic,$Tid,$layout,$MainTable,$NewDex);
			$Forms = $Form->DrawForm(array($lanSignUp));
			//if($viewsonic!="view"){
			//	$Forms=str_replace('$lan',"$lan",$Forms);
			//}
			$Forms['grouping']= SOAPField($Forms['grouping']);
			mark($Forms,"FORMS");

			Gardevoir($Forms);
			break;
		case "list":
			$_SESSION['Patient']=$_GET['dataid'];
			$List= new Listing($MainTable,$layout,array(7=>'gita_patient',6=>'form_id',2=>"patientid, prefix, FName, LName, dob, sex, address, desa, district",8=>'FName',9=>"prefix,FName, LName", 4=>$Tid, 10=>"prefix,FName, LName"));
		
			foreach($List->Gardevoir as $y=>$x){
				if(strpos($y, 'pre') === false && strpos($y, 'post') === false) {
				//	mark($y,"THIS GARDE ");
					$List->Gardevoir[$y] .="<td><a href=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_patient&job=5&dataid=$y>Buat Kunjungan</a></td>";
				}
			}
			$List->Gardevoir();
			break;
		}

		
	}

}

$LogContent=array2arrow($registered); //For Logging

	/*
	=====================================================================================
	// -----------------------------------ENGINE---------------------------
	===========================================================================================
	*/


function SOAPField($Fields){
	//DB handling
	$symptomps=$GLOBALS['symtomp']->GoFetch();
	$diagnose=$GLOBALS['diagnosis']->GoFetch();
	mark("JSON");
	echo "<div id=dxsamson hidden>". json_encode($diagnose) ."</div>";

	//Making list
	////Symptomp
	$SYM = "<datalist id='symptomp'>";
		foreach($symptomps as $x){
			$li = SymptompPhraser($x);
			$SYM .= "<option value='". SymptompPhraser($x,", ") ."'>". SymptompPhraser($x) ."</option>";
		}
	$SYM .= "</datalist>";
	echo $SYM;

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

	//Subject
	$New=" <br>
			<label for='SubjectF'>". $GLOBALS['lanSOAPSubject'] ."</label> : 
			<input type=text list=symptomp id='SubjectF' size=60>
			<span class=tooltiptext id='Subjectguide'>". $GLOBALS['lanSymptomp'] ."</span>
			<div class=FieldList id='SubjectD'></div>
			<input type=hidden id='SubjectH' value='empty' name='SubjectH'>		
		";
	$Fields['$lanSOAPSubject_fielding']=Pokeball($Fields['$lanSOAPSubject_fielding'],'soap_subject',array('New'=>$New));

	//Diagnosis
	$New="
			<div class=FieldList id=DXD>
			</div>
			<input type=hidden id=DXH name=Diagnosis>
		";
	$Fields['$lanSOAPAssesment_fielding']=Pokeball($Fields['$lanSOAPAssesment_fielding'],'ob_dx_note',array('New2'=>$New),'After');
	
	//Planing
	$New=" <br>
			<label for=TXF>". $GLOBALS['lanSOAPPlaning'] ." : </label>
			<input type=text id=TXF>
			<div class=FieldList id=TXD>
			</div>
			<input type=hidden id=TXH name=Planing>
		";
	$Fields['$lanSOAPPlaning_fielding']=Pokeball($Fields['$lanSOAPPlaning_fielding'],'soap_planing',array('New3'=>$New));

	//Blood Preassure
	$New=" <input type=hidden id=vt_BP_Sys name=vt_BP_Sys>
			<input type=hidden id=vt_BP_Dia name=vt_BP_Dia>
		";
	$Fields['$lanSOAPObject_fielding']=Pokeball($Fields['$lanSOAPObject_fielding'],'vt_BP',array('New4'=>$New),'After');

	return $Fields;
}



//------------------------DAUH TUKAD SCRIPT-------------------------
echo "<script src=Engine/jsaction.js></script>";



?>
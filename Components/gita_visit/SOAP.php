<?
include "Components/gita_visit/newMedicine.html";

defined('GitaEmr') or Die($UnatuhorizedAccess);

if(bouncer()){
	$MainTableName="com_gita_visit_soap";
    $layout= new GoodBoi('layout'); //Declare an Object "$su_field" with class that used to connect to database with table "pre_layout". GoodBoi is class used for MySQL DB things. You get it? Good Boi...
	$MainTable = new GoodBoi($MainTableName); //Open MySQL connection to 'Staff' Table (Mainly for input to DB)
	$option = new GoodBoi ('list_list');
	$symtomp = new GoodBoi ('com_gita_visit_symptomp');
	$diagnosis = new GoodBoi ('com_gita_visit_diagnosis');
	$FieldID = "gita_visit_soap";
    $Tid='visitid';
	$VisitID= $VisitID? $VisitID: $_GET['dataid'];

    switch($_GET['job']){ // Decide which method should be used to display?
		case 1:
			$viewsonic="reg";
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
		$registered=PhoenixDown($registered);
		$registered['PXH']=NewMedicine($registered['PXH'],$registered['NewMedH']);
		$Remover=array(
			'stat_BMI_int',
			'DXF',
			'PlanningF',
			'SubjectF',
			'vt_BP',
			'NewMedH'
		);
		$RemoverLike=array('ob_');
		

		//New Diagnosis
		//1. Check if Diagnosis is new.
		//2. If yes, insert it into diagnosis list
		$registered['DXH']=DxEater($registered['DXH']);
		$registered['soap_subject']=DxEater($registered['soap_subject'],'Sym');

		






		$registered=RemahRemah($registered,$Remover,$RemoverLike);

		$registered=array_map('ArraySerialize',$registered);
		
		
		//DEBUG
		if(!empty($_SESSION['DeFlea'])){ 
			mark($registered,"Refined POST");
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
			
			
			$BARK= new Snorlax ($Tid,$MainTableName,$registered,null,'New',$MainTable,FALSE);
			OkDialog($lanNewVisitT,$lanNewVisitC,FALSE);

			$VisitID = $BARK -> IVs();
			
			$VisitTab = new GoodBoi('com_gita_visit');

			$VisitData['soap'] = $BARK -> IVs();

			$VisitTab -> GoBark($VisitData);


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
			
			$registered[$Tid] = $VisitID;
			$BARK= new Snorlax ($Tid,$MainTableName,$registered,null,'Edit',$MainTable);

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
			//$VisitID= $VisitID? $VisitID: $_GET['dataid'];
			case "reg":
					/*
			$Form = new Smeargle($FieldID,$viewsonic,$Tid,$layout,$MainTable,$EditPatient);
			$Forms = $Form->DrawForm(array($lanSignUp));
			//if($viewsonic!="view"){
			//	$Forms=str_replace('$lan',"$lan",$Forms);
			//}
			$Forms['grouping']= History($Forms['grouping']);
			*/
			$Forms = new Smeargle($FieldID,$viewsonic,array('FHeader'=>$lanPatientDetailFormHeader,'DataTable'=>$MainTableName,'DataKey'=>$Tid,'DataID'=>$VisitID));
			$Forms = $Forms -> Start();
			$Forms= DauhTukadScript($Forms); 
			markA($Forms,"FORMS");
			Gardevoir($Forms);
			break;
		case "list":
			//LogPatient($_GET['dataid']);
			$List = new Imperial(null,array( 'MySQL' => array (
																'table'=>$MainTableName,
																'id' => 'visitid',
																'main' => array('del'=>' ', 'data'=> array('patient')),
																'sub' => array('del'=>' || ', 'data'=>array('time','visit_type')),
																'hidden' => array('DXH','PXH','SubjectF'),
																'onClick' => 'mod=gita_visit&job=4',
																'button1' => array('DOM'=>"<i class=\"fa fa-pencil\"></i>",'link'=>'mod=gita_visit&job=3','toolTip'=>$lanEdit),
																),
												'heading' => $lanSOAPList, 
												'filter' => 'top'   
												)
												);
												$List -> Draw($List->Aang('Patient','Sex'));
			break;
			}
	
			
		}
	
	}
	/*
		=====================================================================================
		// -----------------------------------ENGINE---------------------------
		===========================================================================================
		*/
	

		function DauhTukadScript($Fields){
			//DB handling
			////Return the DB as json and embed into hidden element to be passed to javascript
			$symptomps=$GLOBALS['symtomp']->GoFetch();
			//echo "<div id=sympsamson hidden>". json_encode($symptomps) ."</div>";

			$diagnose=$GLOBALS['diagnosis']->GoFetch();
			echo "<div id=dxsamson hidden>". json_encode($diagnose) ."</div>";

			$VxT = new GoodBoi('com_gita_visit_soap');
			$Vx = $VxT->GoFetch();
			$Vx = json_encode($Vx);
			echo "<div id='vxson' class='w3-hide' hidden>$Vx</div>";
			
			$PxT = new GoodBoi('com_gita_patient');
			$Px = $PxT->GoFetch();
			$Px = json_encode($Px);
			echo "<div id='pxson' class='w3-hide' hidden>$Px</div>";

			$Plan = new GoodBoi('com_gita_medicine');
			$Planning = $Plan -> GoFetch();
			$Planning = json_encode($Planning);
			echo "<div id='planning_jonson' class='w3-hide' hidden>$Planning</div>";
			
			$Abv = new GoodBoi('com_gita_medicine_abbreviation');
			$Abv = $Abv -> GoFetch();
			$Abv = json_encode($Abv);
			echo "<div id='abv_jonson' class='w3-hide' hidden>$Abv</div>";

			$MFormN = $Plan -> GoFetch(null,"DISTINCT form_n");
			$MFormN = json_encode($MFormN);
			echo "<div id='MFormN_jonson' class='w3-hide' hidden>$MFormN</div>";
			$MType = $Plan -> GoFetch(null,"DISTINCT type");
			$MType = json_encode($MType);
			echo "<div id='MType_jonson' class='w3-hide' hidden>$MType</div>";


			//Language
			/////Symptomp
				$languageSymptomp = array($GLOBALS['lanSymtomp'],$GLOBALS['lanSymtompSep1'],$GLOBALS['lanSymtompSep2'],$GLOBALS['lanSymtompSep3'],$GLOBALS['lanSymtompSep4'],$GLOBALS['lanSymtompSep5'],$GLOBALS['lanSymtompSep6'],$GLOBALS['lanSymtompSep7'],$GLOBALS['lanSymtompSep8']);
				$languageSymptomp = json_encode($languageSymptomp);
				echo "<span id=lanSymptomp hidden>$languageSymptomp</span>";
			/////BMI
				$languageBMI = json_encode(array($GLOBALS['comvisitBMIVSU'],$GLOBALS['comvisitBMISU'],$GLOBALS['comvisitBMIU'],$GLOBALS['comvisitBMIN'],$GLOBALS['comvisitBMIO'],$GLOBALS['comvisitBMIOI'],$GLOBALS['comvisitBMIOII'],$GLOBALS['comvisitBMIOIII']));
				echo "<span id=languageBMI hidden>$languageBMI</span>";
			
		
			//Making list

			///Symptomp
				$SYM = "<datalist id='list_SubjectF'>";
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


			





			
			//Name Grouping
				$New="<div class='w3-row'>";
				$Fields['Group_$lanIdentitiy']=Pokeball($Fields['Group_$lanIdentitiy'],'prefix',array('New6'=>$New),'Before');
				$New="</div>";
				$Fields['Group_$lanIdentitiy']=Pokeball($Fields['Group_$lanIdentitiy'],'LName',array('New7'=>$New),'After');
	
			
			//Default Value FDXD
			$PSx=  $GLOBALS['VisitID']? $VxT->GoFetch("WHERE visitid='". $GLOBALS['VisitID'] ."'") : null;
			


			//HIDDEN FIELD & PLACING LIST

			// Subject

				$New ="<ul class='w3-ul' id=SubjectD>
					". SymList($PSx[0]['soap_subject'],'Sym',$GLOBALS['viewsonic']) ."
						</ul>
					<div class=FieldList id='SubjectD'></div>
					<input type=hidden id='SubjectH' value='empty' name='soap_subject'>	";
					$Fields['Group_$lanSOAPSubject']=Pokeball($Fields['Group_$lanSOAPSubject'],'SubjectF',array('New1'=>$New),'After');

			// Blood Pressure

				$New ="
					<input type=hidden id='vt_BP_Sys' value='empty' name='Systole'>
					<input type=hidden id='vt_BP_Dia' value='empty' name='Diastole'>	";
					$Fields['Group_$lanSOAPSubject']=Pokeball($Fields['Group_$lanSOAPSubject'],'SubjectF',array('New10'=>$New),'After');

			//Diagnosis
			$New="
					<ul class='w3-ul' id=DXD>
					". SymList($PSx[0]['DXH'],'Dx',$GLOBALS['viewsonic']) ."
					</ul>
					<input type=hidden id=DXH name=DXH>
				";
			$Fields['Group_$lanSOAPAssesment']=Pokeball($Fields['Group_$lanSOAPAssesment'],'ob_dx_note',array('New2'=>$New),'After');

			//Planning
			$New="
					<ul class='w3-ul' id=PXD>
					". MedListGenerator($PSx[0]['PXH'],$GLOBALS['viewsonic'],array()) ."
					</ul>
					<input type=hidden id=PXH name=PXH>
					<ul id=NewMedData hidden></ul>
					<input type=hidden id=NewMedH name=NewMedH>
				";
			$Fields['Group_$lanSOAPPlaning']=Pokeball($Fields['Group_$lanSOAPPlaning'],'ob_extra',array('New193'=>$New),'After');

			//Grouping
				///Diagnosis
				$New="<div class='w3-row '>";
				$Fields['Group_$lanSOAPAssesment']=Pokeball($Fields['Group_$lanSOAPAssesment'],'ob_dx_location',array('New8'=>$New),'Before');
				$New="</div>";
				$Fields['Group_$lanSOAPAssesment']=Pokeball($Fields['Group_$lanSOAPAssesment'],'ob_dx_note',array('New9'=>$New),'After');

				///Planning
				$New="<div id=editedP class='w3-row w3-hide'>
						<div id=PID hidden></div>
						<div id=PName class='w3-col m6'></div>
						<div id=PForm class='w3-col m3'></div>
						<div id=PNoDiv class='w3-col m3'>
							<label for=PNo>NO </label>
							<input type=number id=PNo hidden />
							<span hidden id='DataListHolder'></span>
						</div>
						</div>";
				$Fields['Group_$lanSOAPPlaning']=Pokeball($Fields['Group_$lanSOAPPlaning'],'PlanningF',array('New871'=>$New),'After');

				$New="<div id='MedAttr' class='w3-row w3-cell-row'>";
				$Fields['Group_$lanSOAPPlaning']=Pokeball($Fields['Group_$lanSOAPPlaning'],'ob_qday',array('New33'=>$New),'Before');
				$New="</div>";
				$Fields['Group_$lanSOAPPlaning']=Pokeball($Fields['Group_$lanSOAPPlaning'],'ob_extra',array('New34'=>$New),'After');

				$New="<ul id=teraphy class='w3-ul w3-card-4' style=width:300px></ul>";
				$Fields['Group_$lanSOAPPlaning']=Pokeball($Fields['Group_$lanSOAPPlaning'],'New34',array('New3412'=>$New),'After');
	
					
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
		
		$LogContent=array2arrow($registered); //For Logging

		echo "<button id='LOLOK'>Modal</button>";
	/*
	=====================================================================================
	// -----------------------------------Draw Signup Form---------------------------
	===========================================================================================
	


	
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
/*

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
*/

echo "<script src=Engine/medicine.js></script>";
?>
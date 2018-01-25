<?
defined('GitaEmr') or Die($UnatuhorizedAccess);

if(bouncer()){

    $layout= new GoodBoi('layout'); //Declare an Object "$su_field" with class that used to connect to database with table "pre_layout". GoodBoi is class used for MySQL DB things. You get it? Good Boi...
	$MainTable = new GoodBoi('com_gita_visit'); //Open MySQL connection to 'Staff' Table (Mainly for input to DB)
	$option = new GoodBoi ('list_list');
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
    
	


	
	// Entry Action
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
	
	//===============================================================
	//Display Method
	//========================================================

	if(!empty($_POST)){
	$Px= $MainTable->GoFetch("WHERE $Tid='". $_POST['patientid'] ."'");
	$PxId=$Px[0]['patientid'];
	$PxName=NameArrangement($Px[0]['FName'],$Px[0]['MName'],$Px[0]['LName']);
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


?>
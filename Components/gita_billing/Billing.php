<?

defined('GitaEmr') or Die($UnatuhorizedAccess);

if(bouncer()){
$MainTableName="management_inventory_medicine";
$layout= new GoodBoi('layout'); //Declare an Object "$su_field" with class that used to connect to database with table "pre_layout". GoodBoi is class used for MySQL DB things. You get it? Good Boi...
$MainTable = new GoodBoi($MainTableName); //Open MySQL connection to 'Staff' Table (Mainly for input to DB)
$option = new GoodBoi ('list_list');
$medicineTab = new GoodBoi ('com_gita_visit_medicine');
$diagnosis = new GoodBoi ('com_gita_visit_diagnosis');
$FieldID = "gita_visit_soap";
$Tid='visitid';
$VisitID= $VisitID? $VisitID: $_GET['dataid'];
$CurrencyTab = new GoodBoi ('com_gita_billing_currency');
$Currency= $CurrencyTab -> GoFetch();
echo "<div id='CurrencyUnit' hidden>". ArraySerialize($Currency) ."</div>";

$visitId = $_GET['visitid'];

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


		$BARK= new Snorlax ($Tid,$MainTableName,$registered,null,'New',$MainTable);
		OkDialog($lanNewVisitT,$lanNewVisitC,FALSE);

		$VisitID = $BARK -> IVs();
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
// -----------------------------------Draw Form---------------------------
===========================================================================================
*/



if(!empty($Validation->SignUpError) OR empty($_POST) OR $viewsonic='view'){ //Display registration Form if error occured o if no input yet

	//Connect to Visit Table and get corrspondencing SOAP
	$VisitTab = new GoodBoi('com_gita_visit');
	$Visit = $VisitTab -> GoFetch("WHERE id='$visitId'");
	$SOAPId = $Visit[0]['soap'];
	
	//Currency <select>
	foreach($Currency as $y => $x){
		$selected = $x['id']==$settingBillingDefUnit? "Selected" : FALSE;
		$CurrencyOption .= "<option value='". $x['id'] ."' convRate='". $x['convToUSD'] ."' $selected>". $x['name'] ."</option>";
		unset($selected); 
	}

	switch ($viewsonic){ // If viewsonic is empty, thats mean user have unatuhorized access
		case "view":
		case "edit":
		//$VisitID= $VisitID? $VisitID: $_GET['dataid'];
		case "reg":

			$button = "<input type=submit class='w3-button w3-lime' value='$lanBilled'>";

			$billForm['pre'] = "
								<h4 class='w3-panel w3-lime'>$settingBillingHeader</h4>
								<form id='gita_billing' class='w3-card-4 w3-white js_noSubmitEnter' action=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_billing&job=4&visitid=$VisitID method='POST' enctype=multipart/form-data >
								<ul class='w3-panel w3-ul'>
								";

			//Default billing itmes
			$billForm['items']["def_pre"] = "<li>";

			foreach($settingBillingDefServices as $y => $x ){
				$billForm['items']["def_$y"] = "
										<div class='w3-row-padding'>
											<label class='w3-col m12' >". $x['label'] ."</label>
											<div class='w3-col m1 js_currency_unit' unit='". $x['unit'] ."'>
												". FilterArray($Currency,'id',$settingBillingDefUnit)[0]['symbol'] ."
											</div>
											<div class='w3-col m7'>
												<input type='number' class='w3-input js_price' name='def_".$y."_price' id='def_".$y."_price' value='". $x['price'] ."'>
											</div>
											<div class='w3-col m4'>
												<select class='w3-select js_select_currency' name='def_".$y."unit' id='def_".$y."unit' >
													$CurrencyOption
												</select>
											</div>
										</div>
										";
			}

			$billForm['items']["def_post"] = "</li>";


			//Medicine billing itmes

			$billForm['items']["med_pre"] = "<li><h5>$lanMedicineFee</h5></li>
											<li>";

			$SOAPTab = new GoodBoi("com_gita_visit_soap");
			$SOAP = $SOAPTab -> GoFetch("WHERE visitid='". $SOAPId ."'");
			$Planning = UnJson($SOAP[0]['PXH']);
			$Planning = AppendMed($Planning);
			$medInventoryTab = new GoodBoi("management_inventory_medicine");
			
			foreach($Planning as $y => $x ){

				
				//Make radio button for Medicine Inventory
				$medInventory = $medInventoryTab -> GoFetch("WHERE medId='". $x['id'] ."' AND  location='$SettingCurrentFacility' AND stock > 0");
				foreach($medInventory as $m) {
					$radio .= "<input type='radio' class='w3-radio' name='radio_med_$y' value='". $m['id'] ."'>Isi ". $m['ContentPerPcs'] ."/Pcs || Harga ". $m['priceUnit'] ." ". $m['price'] ." || Expired ". $m['expDate'] ." || Sisa Stok ". $m['stock'] ." || Rak ". $m['shelve'] ." || Supplier ". $m['supplier'] ."<br>";
				}
				
				
				$billForm['items']["med_$y"] = "
										<div class='w3-row-padding'>
											<label class='w3-col m12' >". $x['name'] ."</label>
											<div class='w3-col m1 js_currency_unit' unit='". $x['priceUnit'] ."'>
												". FilterArray($Currency,'id',$medInventory[0]['priceUnit'])[0]['symbol'] ."
											</div>
											<div class='w3-col m7'>
												<input type='number' class='w3-input js_price' name='med_".$y."_price' id='med_".$y."_price' value='". $medInventory[0]['sellingPrice'] ."'>
											</div>
											<div class='w3-col m4'>
												<select class='w3-select js_select_currency' name='med_".$y."unit' id='med_".$y."unit' >
													$CurrencyOption
												</select>
											</div>
											<div class='w3-col m12'>
												$radio
											</div>
										</div>
										";

				
			}

			$billForm['items']["med_post"] = "</li>";

			//Additional Manual Inserted Items
			$billForm['items']["manual_pre"] = "<li><h5>$lanBillingAdditionalLabel</h5>";
			$billForm['items']["manual"] = "
										<div class='w3-row-padding'>
											<label class='w3-col m12' >$lanBillingBill</label>
											<div class='w3-col m4'>
											<input type='text' id='manual_label' placeholder='$lanBillingAdditionalInput' class='w3-input'>
											</div>
											<div class='w3-col m1 js_currency_unit' unit='". $x['unit'] ."'>
												". FilterArray($Currency,'id',$settingBillingDefUnit)[0]['symbol'] ."
											</div>
											<div class='w3-col m3'>
												<input type='number' class='w3-input ' name='manual_price' id='manual_price' value=''>
											</div>
											<div class='w3-col m4'>
												<select class='w3-select js_select_currency' name='manual_unit' id='manual_unit' >
													$CurrencyOption
												</select>
											</div>
										</div>
										<ul class='w3-ul' id='billing_manual_additional_list'>
										</ul>
										";

			$billForm['items']["manual_post"] = "</li>";


			/////////////// DISCOUNT ////////////////////


			//Default Discount itmes
			$billForm['discount']["def_pre"] = "<li><h5>$lanDiscount</h5></li>
												<li>";

			foreach($settingBillingDefDiscount as $y => $x ){
				$billForm['discount']["def_$y"] = "
										<div class='w3-row-padding js_discount_div'>
											<label class='w3-col m12' >". $x['label'] ."</label>
											<div class='w3-col m2'>
												<input type='text' class='w3-input js_disc' name='def_".$y."_price' id='def_disc_".$y."' value='". $x['discount'] ."'>
											</div>
											<div class='w3-col m1 js_currency_unit' unit='". $x['unit'] ."'>
												". FilterArray($Currency,'id',$settingBillingDefUnit)[0]['symbol'] ."
											</div>
											<div class='w3-col m4'>
												<input type='text' class='w3-input js_discount' name='def_disc_".$y."_price' id='def_disc_".$y."_price' value='' disable>
											</div>
											<div class='w3-col m4'>
												<select class='w3-select js_select_currency' name='def_".$y."unit' id='def_disc_".$y."unit' >
													$CurrencyOption
												</select>
											</div>
										</div>
										";
			}

			$billForm['discount']["def_post"] = "</li>";

			//Additional Manual Inserted Discount
			$billForm['discount']["manual_disc_pre"] = "<li><h5>$lanBillingAdditionalLabel</h5>";
			$billForm['discount']["manual_disc"] = "
										<div class='w3-row-padding js_discount_div'>
											<label class='w3-col m12' >$lanBillingDiscount</label>
											<div class='w3-col m4'>
											<input type='text' id='manual_disc_label' placeholder='$lanBillingAdditionalInput' class='w3-input'>
											</div>
											<div class='w3-col m1'>
												<input type='text' class='w3-input js_disc' name='manual_disc_price' id='manual_disc_present' value=''>
											</div>
											<div class='w3-col m1 js_currency_unit' unit='". $x['unit'] ."'>
												". FilterArray($Currency,'id',$settingBillingDefUnit)[0]['symbol'] ."
											</div>
											<div class='w3-col m1'>
												<input type='text' class='w3-input' name='manual_disc_price' id='manual_disc_price' value=''>
											</div>
											<div class='w3-col m4'>
												<select class='w3-select js_select_currency' name='def_".$y."unit' id='manual_disc_unit' >
													$CurrencyOption
												</select>
											</div>
										</div>
										<ul class='w3-ul' id='billing_manual_additional_discount'>
										</ul>
										";

			$billForm['discount']["manual_disc_post"] = "</li>";

			// CLOSURE

			$billForm['post'] = "</ul>
								<input type=hidden id='jonson_bills'>
								<input type=hidden id='jonson_discounts'>
								<input type=hidden id='price_bills'>
								<input type=hidden id='price_discounts'>
								<input type=hidden id='price_total'>
								<h5 class='w3-row-padding'>
									<span class='w3-half'>$lanRawPrice</span>
									<span class='w3-half  w3-right'>
										<span id='billingRawPrice' class=' w3-right'>0</span>
										<span class=' w3-right'>". FilterArray($Currency,'id',$settingBillingDefUnit)[0]['symbol'] ." </span>
									</span>
								</h5>
								<h5 class='w3-row-padding'>
									<span class='w3-half'>$lanTotalDiscount</span>
									<span class='w3-half'>
										<span id='billingDiscountPrice' class=' w3-right'>0</span>	
										<span class=' w3-right'>". FilterArray($Currency,'id',$settingBillingDefUnit)[0]['symbol'] ." </span>						
									</span>
								</h5>
								<h3 class='w3-row-padding'>
									<span class='w3-half'>$lanTotalPrice</span>
									<span class='w3-half'>
										<span id='billingTotalPrice'  class=' w3-right'>0</span>
										<span class=' w3-right'>". FilterArray($Currency,'id',$settingBillingDefUnit)[0]['symbol'] ." </span>
										
									</span>
								</h3>
								<div class='w3-panel w3-center'> $button </div>
								</form>";

			markA($billForm,"FORM");

			Gardevoir($billForm);

		break;
	case "list":
		//LogPatient($_GET['dataid']);
		$List= new Listing($MainTable,$layout,array(7=>$MainTableName,6=>'form_id',2=>"visitid, time, patient, provider, assistant_provider, visit_type",8=>'patient',9=>"time, patient, provider, visit_type", 4=>$Tid));

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


echo "<script>$(document).ready(function() {
		$('#billingRawPrice').text(Sum41('.js_price'));
		$('.js_disc').each(function() {
			discF = $(this).closest('.js_discount_div').find('.js_discount')[0];
			$(discF).val(GimmeDiscount($('#billingRawPrice').text(),$(this).val()));
		});
		$('#billingDiscountPrice').text(Sum41('.js_discount'));
		$('#billingTotalPrice').text($('#billingRawPrice').text()-$('#billingDiscountPrice').text());
	});</script>";
echo "<script src=Engine/medicine.js></script>";
?>

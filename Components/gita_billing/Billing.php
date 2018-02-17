<?

defined('GitaEmr') or Die($UnatuhorizedAccess);

if(bouncer()){
$MainTableName="com_gita_billing_bill";
$layout= new GoodBoi('layout'); //Declare an Object "$su_field" with class that used to connect to database with table "pre_layout". GoodBoi is class used for MySQL DB things. You get it? Good Boi...
$MainTable = new GoodBoi($MainTableName); //Open MySQL connection to 'Staff' Table (Mainly for input to DB)
$option = new GoodBoi ('list_list');
$medicineTab = new GoodBoi ('com_gita_visit_medicine');
$diagnosis = new GoodBoi ('com_gita_visit_diagnosis');
$FieldID = "gita_visit_soap";
$Tid='id';
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
	
	$Additional= array('billedAt'=>Date2SQL()); //Aditional values to record on DB on registration

	/////////////////////////////////////////////////////////////////


	$registered=array_merge ($newera,$Additional);



	//DEBUG
	if(!empty($_SESSION['DeFlea'])){
		mark($registered,"Refined POST");
	}





}


/*
========================================================================================
//--------------------New Bill-------------------
=======================================================================================
*/

if(!empty($_POST) AND $_GET['job']==1){ // Validate if form already posted
	mark("NEW VISIT");
	$Validation=new FieldValidation ("Visit",$FieldID,$layout,$MainTable,array(3=>1));



	if(empty($Validation->SignUpError)){ // Register if no error occured


		$BARK= new Snorlax ($Tid,$MainTableName,$registered,null,'New',$MainTable,FALSE);
		OkDialog($lanNewVisitT,$lanNewVisitC,FALSE);

		$VisitTab = new GoodBoi('com_gita_visit');

		$VisitData['billing'] = $BARK -> IVs();

		$VisitTab -> GoBurry($VisitData,"WHERE id='$visitId' ");


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
		//Search visit ID
		case "reg":

			$button = "<input type=submit class='w3-button w3-lime' value='$lanBilled'>";

			$billForm['pre'] = "
								<h4 class='w3-panel w3-lime'>$settingBillingHeader</h4>
								<form id='gita_billing' class='w3-card-4 w3-white js_noSubmitEnter' action=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_billing&job=1&visitid=$visitId method='POST' enctype=multipart/form-data >
								<ul class='w3-panel w3-ul'>
								";

			//Default billing itmes
			$billForm['items']["def_pre"] = "<li>";

			foreach($settingBillingDefServices as $y => $x ){
				$billForm['items']["def_$y"] = "
										<div class='w3-row-padding js_Bills_Items'>
											<label class='w3-col m12 js_bill_label' >". $x['label'] ."</label>
											<div class='w3-col m1 ' unit='". $x['unit'] ."'>
												". FilterArray($Currency,'id',$settingBillingDefUnit)[0]['symbol'] ."
											</div>
											<div class='w3-col m7'>
												<input type='number' class='w3-input js_price'id='def_".$y."_price' value='". $x['price'] ."'>
											</div>
											<div class='w3-col m2'>
												<select class='w3-select js_select_currency'id='def_".$y."unit' >
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
					$radio .= "<input type='radio' class='w3-radio' value='". $m['id'] ."'>Isi ". $m['content'] ."/Pcs || Harga ". $m['priceUnit'] ." ". $m['sellingPrice'] ." || Expired ". $m['expDate'] ." || Sisa Stok ". $m['stock'] ." || Rak ". $m['shelve'] ." || Supplier ". $m['supplier'] ."<br>";
				}
				
				
				$billForm['items']["med_$y"] = "
										<div class='w3-row-padding js_Bills_Items'>
											<label class='w3-col m12 js_bill_label' >". $x['name'] ."</label>
											<div class='w3-col m1 js_currency_unit' unit='". $x['priceUnit'] ."'>
												". FilterArray($Currency,'id',$medInventory[0]['priceUnit'])[0]['symbol'] ."
											</div>
											<div class='w3-col m3'>
												<input type='number' class='w3-input js_pricePcs' id='med_".$y."_price' value='". $medInventory[0]['sellingPrice'] ."'>
											</div>
											<div class='w3-col m4'>
												<select class='w3-select js_select_currency'  id='med_".$y."unit' >
													$CurrencyOption
												</select>
											</div>
											<div class='w3-col m1 js_medQtt' unit='". $x['unit'] ."'>
												(" . $x['qtt'] . ")
											</div>
											<div class='w3-col m3'>
												<input type='number' class='w3-input js_price js_priceQtt'id='def_".$y."_price' value='".$medInventory[0]['sellingPrice'] * $x['qtt']  ."' disabled>
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
											<label class='w3-col m12 ' >$lanBillingBill</label>
											<div class='w3-col m4'>
											<input type='text' id='manual_label' placeholder='$lanBillingAdditionalInput' class='w3-input'>
											</div>
											<div class='w3-col m1 js_currency_unit' unit='". $x['unit'] ."'>
												". FilterArray($Currency,'id',$settingBillingDefUnit)[0]['symbol'] ."
											</div>
											<div class='w3-col m3'>
												<input type='number' class='w3-input '  id='manual_price' value=''>
											</div>
											<div class='w3-col m4'>
												<select class='w3-select js_select_currency'  id='manual_unit' >
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
											<label class='w3-col m12 js_bill_label' >". $x['label'] ."</label>
											<div class='w3-col m2'>
												<input type='text' class='w3-input js_disc'  id='def_disc_".$y."' value='". $x['discount'] ."'>
											</div>
											<div class='w3-col m1 js_currency_unit' unit='". $x['unit'] ."'>
												". FilterArray($Currency,'id',$settingBillingDefUnit)[0]['symbol'] ."
											</div>
											<div class='w3-col m4'>
												<input type='text' class='w3-input js_discount'  id='def_disc_".$y."_price' value='' disable>
											</div>
											<div class='w3-col m4'>
												<select class='w3-select js_select_currency' id='def_disc_".$y."unit' >
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
										<div class='w3-row-padding'>
											<label class='w3-col m12' >$lanBillingDiscount</label>
											<div class='w3-col m4'>
											<input type='text' id='manual_disc_label' placeholder='$lanBillingAdditionalInput' class='w3-input'>
											</div>
											<div class='w3-col m1'>
												<input type='text' class='w3-input js_disc'  id='manual_disc_present' value=''>
											</div>
											<div class='w3-col m1 js_currency_unit' unit='". $x['unit'] ."'>
												". FilterArray($Currency,'id',$settingBillingDefUnit)[0]['symbol'] ."
											</div>
											<div class='w3-col m1'>
												<input type='text' class='w3-input' id='manual_disc_price' value=''>
											</div>
											<div class='w3-col m4'>
												<select class='w3-select js_select_currency' id='manual_disc_unit' >
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
								<input type=hidden id='jonson_bills' name='itemList'>
								<input type=hidden id='jonson_discounts' name='discountList'>
								<input type=hidden id='price_bills' name='bill'>
								<input type=hidden id='price_discounts' name='discount'>
								<input type=hidden id='price_total' name='total'>
								<input type=hidden name='patient' value='". $SOAP[0]['provider'] ."'>
								<input type=hidden name='billedBy' value='". $SOAP[0]['patient'] ."'>
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
			switch ($_GET['filter']){
				case ('patient'):
					$cond = "WHERE patient='" . $_GET['dataid'] . "'";
					break;
				case ('day'):
					$cond = "WHERE billedAt >= '" . $_GET['dataid'] . " 00:00:00' AND billedAt < '" . $_GET['dataid'] . " 23:59:59'";
					break;
			}
			$List = new Imperial(null,array( 'MySQL' => array (
							'table'=>$MainTableName,
							'id' => 'id',
							'main' => array('del'=>' ', 'data'=> array('patient')),
							'sub' => array('del'=>' : ', 'data'=>array('billedAt','total')),
							'hidden' => array('itemList','discountList'),
							'onClick' => 'mod=gita_visit&job=4',
							'button1' => array('DOM'=>"<i class=\"fa fa-pencil\"></i>",'link'=>'mod=gita_visit&job=3','toolTip'=>$lanEdit),
							'condition'=> $cond . ' ORDER BY billedAt DESC'),
					'heading' => $lanSOAPList, 
					'filter' => 'top'   
			)
			);
			$PatientTab = new GoodBoi ("com_gita_patient");
			$Patient = $PatientTab->GoFetch();
			$List->RefineRefined('patient',$Patient,array('Which'=>'main','Organize'=>'patientid','Function'=>'FullName'));
			$List -> Draw($List->Zebra());
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
		$('.js_TotalPcsPrice').text(Number($(this).parent().find('.js_pricePcs').val() ) * Number($(this).parent().find('.js_medQtt').text()));
	});</script>";
echo "<script src=Engine/medicine.js></script>";
?>

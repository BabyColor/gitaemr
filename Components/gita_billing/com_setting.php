<?
$settingBillingDefUnit = 'IDR';
$settingBillingHeader = 'Tagihan';

//Consultation Fee
$settingBillingDefServices['consultationFee']['price'] = 50000;
$settingBillingDefServices['consultationFee']['label'] = $lanConsultationFee;
$settingBillingDefServices['consultationFee']['unit'] = $settingBillingDefUnit;
$settingBillingDefServices['consultationFee']['enable'] = TRUE;

//Administartion Fee Fee
$settingBillingDefServices['adminFee']['price'] = 5000;
$settingBillingDefServices['adminFee']['label'] = $lanAdministrationFee;
$settingBillingDefServices['adminFee']['unit'] = $settingBillingDefUnit;
$settingBillingDefServices['adminFee']['enable'] = TRUE;

//Opening Discount
$settingBillingDefDiscount['opening']['discount'] = "10%";
$settingBillingDefDiscount['opening']['label'] = $lanOpeningDiscount;
$settingBillingDefDiscount['opening']['enable'] = TRUE;

?>
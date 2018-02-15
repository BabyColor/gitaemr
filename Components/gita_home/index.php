<?php
$MainTable = new GoodBoi('com_gita_patient'); 
$layout= new GoodBoi('layout');
echo "<div class='w3-card-4 w3-container w3-lime'>
<h1>Selamat datang ". $_SESSION['Name'] . "</h1></div><br>";

echo "<section class='w3-card-4 w3-white w3-panel w3-xxlarge HomePatient'>";
if(empty($_SESSION['Patient'])){
    echo "<div class='w3-card-4 w3-panel w3-white' >Anda belum memilih pasien, silahkan pilih pasien dari <a href=". GetOwnURL() ."> $lanListPatient </a> atau daftarkan <a href=". GetOwnURL() .">$lanNewPatient</a></div>";
    LogPatient($_GET['dataid']);
			$List= new Listing($MainTable,$layout,array(7=>'gita_patient',6=>'form_id',2=>"patientid, prefix, FName, LName, dob, sex, address, desa, district",8=>'FName',9=>"prefix, FName, LName", 4=>'patientid', 10=>"prefix,FName, LName"));
		
		
            $List->Gardevoir();
} else {
    foreach($_SESSION['Px'] as $y=>$x){
        $$y=lan2var($x);
    }
    $FullName=$_SESSION['PName'];
    $Age=getAge($dob);
    echo "Pasien : $FullName, $sex, $Age Tahun";
}
echo "</section>";

?>

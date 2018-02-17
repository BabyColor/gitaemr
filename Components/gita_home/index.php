<?php
$MainTable = new GoodBoi('com_gita_patient'); 
$layout= new GoodBoi('layout');
echo "<div class='w3-card-4 w3-container w3-lime'>
<h1>Selamat datang ". $_SESSION['Name'] . "</h1></div><br>";

echo "<section class='HomePatient'>";
if(empty($_SESSION['Patient'])){
    echo "<div class='w3-card-4 w3-panel w3-white'><div class=' w3-panel w3-whiter' >Anda belum memilih pasien, silahkan pilih pasien dari <a href=". GetOwnURL() ."> $lanListPatient </a> atau daftarkan <a href=". GetOwnURL() .">$lanNewPatient</a></div></div>";
    $List = new Imperial(null,array( 'MySQL' => array (
        'table'=>'com_gita_patient',
        'id' => 'patientid',
        'main' => array('del'=>' ', 'data'=> array('prefix','FName','LName')),
        'sub' => array('del'=>' || ', 'data'=>array('patientid','sex','dob')),
        'hidden' => array('idcard','address'),
        'picture' => 'photo',
        'onClick' => 'mod=gita_patient&job=4',
        'button1' => array('DOM'=>"<i class=\"material-icons\">check</i>",'link'=>'mod=gita_patient&job=5','toolTip'=>$lanChosePatient),
        'button2' => array('DOM'=>"<i class=\"fa fa-pencil\"></i>",'link'=>'mod=gita_patient&job=3','toolTip'=>$lanEdit),
        ),
'heading' => $lanListPatient, 
'filter' => 'top'   
)
);
$List -> Draw($List->Aang('Korra','Sex'));
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

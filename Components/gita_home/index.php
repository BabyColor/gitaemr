<?php

echo "<section class=HomeStaff>
Selamat datang ". $_SESSION['Name'] . "</section>";

echo "<section class=HomePatient>";
if(empty($_SESSION['Patient'])){
    echo "Anda belum memilih pasien, silahkan pilih pasien dari <a href=". GetOwnURL() ."> $lanListPatient </a> atau daftarkan <a href=". GetOwnURL() .">$lanNewPatient</a>";
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

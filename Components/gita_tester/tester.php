<?
$tusbol = array('jantuk',3,true,'asem');
//echo implode(";", $tusbol) ."<br>";

//$sql = "SELECT * FROM Tester WHERE idTester=4"; 
//$result = mysqli_query($ewe, $sql); 
//while($row = mysqli_fetch_assoc($result)) { 
//	$explosion = explode(";",$row['Atr1']);
//	echo $explosion[0];
//}

$test='$yoyo';

$sql = "INSERT INTO `gitaemr`.`list_list` (`option`, `value`) VALUES ('rrdsrrr','". '$test' . "')";

if ($ewe->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
   echo "Error: " . $sql . "<br>" . $ewe->error;
}

echo "<br>". $now ."<br>";
echo date("Y-m-d H:i:s");
echo "<br><br><br>&copy; 2010-". date("Y") ."<br><br><br>";
//echo $now;

$sql2 = "SELECT * FROM list_list";
						$result2=mysqli_query($ewe,$sql2);
						while($i2=mysqli_fetch_assoc($result2)){
							echo $i2['option'] ."---->". $i2['value'] ."<br>";
						}
echo "yayayay";
?>


<form oninput="x.value=(a.value).concat(b.value)" action="tester.php" method="get">0
  <input type="range" id="a" value="50" name="numb1">100
  +<input type="text" id="b" value="50">
  <input type="hidden" name="Semb">
  =<output name="x" for="a b"></output>
  <input type="submit">
</form>
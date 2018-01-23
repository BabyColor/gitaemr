<?php
defined('GitaEmr') or Die($UnatuhorizedAccess);
$Root = $Argument;
$Menu= new GoodBoi('mod_gita_menu');
if(empty($Root)) { $Root='root'; }
$Menu1=$Menu->GoFetch("WHERE parent='". $Root ."' ORDER BY short"); //Nge Fetch dari DB mysql, dadi multidimension array([0]=>array(kolom1=>value1, kolom2=>value2),[1]=>array(kolom1=>value1, kolom2=>value2)... dst)

foreach($Menu1 as $b=>$a){
    echo "<div class=dropdown>";
    echo "<button onclick=DropButton('". $a['id'] ."MD') class='dropbtn'>". lan2Var($a['menu']) ."</button>";
    echo "<div id=". $a['id'] ."MD class=dropdown-content>";
    $Menu2=$Menu->GoFetch("WHERE parent='". $a['id'] ."' ORDER BY short");
    foreach($Menu2 as $y=>$x){
        $URL= empty($x['curl'])? htmlspecialchars( $_SERVER['PHP_SELF'] )."?mod=". $x['mod'] ."&job=". $x['job'] : $x['curl'];
        echo "<a href= $URL >". lan2Var($x['menu']) ."</a>";
        
    }
    echo "</div>";
    echo "</div>";
}

echo "<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function DropButton(xclass) {
  document.getElementById(xclass).classList.toggle('show');
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName('dropdown-content');
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

</script>";

?>
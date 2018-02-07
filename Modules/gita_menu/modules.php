<?php
defined('GitaEmr') or Die($UnatuhorizedAccess);
$Root = $Argument;
$Menu= new GoodBoi('mod_gita_menu');
if(empty($Root)) { $Root='root'; }
$Menu1=$Menu->GoFetch("WHERE parent='". $Root ."' ORDER BY short"); //Nge Fetch dari DB mysql, dadi multidimension array([0]=>array(kolom1=>value1, kolom2=>value2),[1]=>array(kolom1=>value1, kolom2=>value2)... dst)
foreach($Menu1 as $b=>$a){
    echo "<div class='w3-dropdown-hover w3-hide-small '>";
    echo "<button class='w3-padding-large w3-button'>". lan2Var($a['menu']) ."</button>";
    echo "<div id=". $a['id'] ."MD class='w3-dropdown-content w3-bar-block w3-card-4'>";
    $Menu2=$Menu->GoFetch("WHERE parent='". $a['id'] ."' ORDER BY short");
    foreach($Menu2 as $y=>$x){
        $URL= empty($x['curl'])? htmlspecialchars( $_SERVER['PHP_SELF'] )."?mod=". $x['mod'] ."&job=". $x['job'] : $x['curl'];
        echo "<a href= $URL class='w3-bar-item w3-button'>". lan2Var($x['menu']) ."</a>";
        
    }
    echo "</div>";
    echo "</div>";
}

?>
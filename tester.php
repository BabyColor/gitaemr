<?php
if(!empty($_SESSION['DeFlea'])){ mark("DEBUG MODE");}
echo "=================TESTER===========================<br><br>";

$A1=array("Pis"=>"Bolong");
$A2=array("TOKAI"=>"EWE");
$A2 += $A1;
mark ($A2);

/*
echo 'Now:       '. date('Y-m-d  H:m:s') ."\n";
$AAA='$lanMoreThanFriendButLessThanLover';
$BBB=strtr($AAA, array('$lanMoreThanFriendButLessThanLover' => $lanMoreThanFriendButLessThanLover));
mark($BBB, "STR  ");


$Testglob="AMerika";
function Testor(){
    mark($GLOBALS['$Testglob'], "GLOCAL ");
}

Testor();

$yyy= new GoodBoi("list_list");
$alan=$yyy->GoFetch("WHERE cluster='sex'");
foreach($alan as $x){
    $bob= $bob . " " . $x['list_name'];
}
mark($bob," Bob");
$newbob=str_replace('$lan',"$lan",$bob,$uyu) ;

mark($newbob," Bob 2");
mark($uyu, "COUNT");

class TES1{
    protected $MYSQL;

    public function __construct(){
        $this->MYSQL= new GoodBoi("layout");
    }

    public function OLE(){
        $Query = $this->MYSQL->GoFetch();
        mark($Query,"Class in class");
    }
}
$GON = new TES1();
$GON->OLE();
*/

//LastSQLEntry("gemr_Kayu","Time",$number=10,$print=1);

if(!empty($_SESSION['DeFlea'])){ mark(array2arrow($_SESSION," ==> ","<br>"), "This Session<br>");}

echo "<br><br>===================END OF TESTER===========<br><br>";
?>

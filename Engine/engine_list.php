<?php
defined('GitaEmr') or Die($UnatuhorizedAccess);
/*
 ██████╗
██╔════╝
██║     
██║     
╚██████╗
 ╚═════╝
           

************************************************************************************************
             /<                                             
            /<                                              
  |\_______{o}----------------------------------------------------------_
 [\\\\\\\\\\\{*}:::<===================  CONVERTER ===============-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/

function array2csv($arr,$bra=null,$IgnoreBlank=null,$IgnoreExc=null,$sep=" , "){ // Ignore Blank=Ignore value with Blank Key, IgnoreExc=Ignore any value with key started with "Exc-"
  $arr=array_map("ArraySerialize",$arr);
  foreach($arr as $a => $b){
    
    if((empty($b) && !empty($IgnoreBlank)) || (substr( $a, 0, 4 ) == "Exc-" && !empty($IgnoreExc))){ // Seperate the array into key/value and convert it into MySQL string (If key is't empty or not Exception [exc-]
      continue;
    }
    
    if(!empty($counter)){  // Assign "," if not first value
      $aa = $aa . $sep;
      $bb = $bb . $sep;
    }
    
    $aa = $aa . $a ;
    $bb = $bb . $bra . $b . $bra;
    $counter++;
  }
  
  if(!empty($_SESSION['DeFlea'])){ mark($aa,__FUNCTION__ ." Key ==> "); mark($bb,__FUNCTION__ ." Val ==> "); } //Debug
  return array("Key"=>$aa,"Val"=>$bb);
}



function array2arrow($arr,$arrow=" ==> ",$sep="<br/>",$valpre="'",$valpost="'",$keypre="",$keypost=""){ // Turn array into key => value (Array,Arrow,Seperator)
  $arr=array_map("ArraySerialize",$arr);
  foreach($arr as $a => $b){
    if(!empty($counter)){  // Assign seperator if not first value
      $aa = $aa . $sep;
    }
    $aa = $aa. $keypre . $a . $keypost . $arrow . $valpre . $b . $valpost;
    $counter++;
  } 
  return $aa;
}


function Date2SQL($date=null,$Interval=null){
  //if(empty($date)){ unset($date); }
  $date2sql = new DateTime($date);
  if(!empty($Interval)){
    $date2sql->add(new DateInterval($Interval));
  }
  if(!empty($_SESSION['DeFlea'])){ mark($date2sql->format('Y-m-d H:i:s'),"Date2SQL ==> "); } //Debug
  return $date2sql->format('Y-m-d H:i:s');
}

function Blissey($array){ //To Mysqli_real_escape_string an array
  $mysqli= SniffButt();
  $happiny=array();
  foreach($array as $pokemon=>$chansey){
      $chansey=$mysqli->real_escape_string($chansey);
      $chansey=array($pokemon=>$chansey);
      $happiny = $happiny + $chansey ;
  }
  return $happiny;
}

function Array2List($ListArray,$Pre=null,$InPre=null,$InPos=null,$Post=null,$KeySep=null){ //Turn array into <ul>, (Array,Pre Text, In list pretext, IN list postext, Post text)
  $ListArray=array_map("ArraySerialize",$ListArray);
  $Pre .= "<br><ul>";
  if(!empty($KeySep)){ $KeySep="$x $KeySep "; } else {  $KeySep=''; }
	foreach($ListArray as $x=>$i){
     $Pre= $Pre . "<li>$InPre $KeySep $i $InPos</li>";
  }
  if(!empty($_SESSION['DeFlea'])){ mark($Pre . "</ul><br> $Post",__FUNCTION__ ."Returned "); }
  return $Pre . "</ul><br> $Post";
}

function lan2var($lan){ //Turn $lan string into watherever contained in it's corepondenting variable
  if(strpos($lan,'$lan') !== false ) { 
    return $GLOBALS[str_replace('$lan','lan',$lan)]; 
  } else {
    return $lan;
  }
}

function NameArrangement($FName,$MName,$LName,$Pre=null,$Post=null){
  if($GLOBALS['FirstNameFirst']==1){
    return "$Pre $FName $MName $LName $Post";
  } else { 
    return "$Pre $LName, $FName $MName $Post";
  }
}

function ArraySerialize($A){
  if (is_array($A)){ return serialize($A); } else { return $A; }
}
function ArrayUnserialize($A){
  $B=@unserialize($A);
  if ($B !== false){
    return $B;
  } else {
    return $A;
  }
}

function Array2Array($arr,$key,$label){
  $result=array();
  foreach($arr as $x){
    $result += array($x[$key]=>$x[$label]);
  }
  return $result;
}

function csv2csv($csv,$sep=', ',$resep=',',$pre="'",$pst="'"){
  // (CSV string, original delimeter, desired delimeter, string to put before, and after each values)
  $arr=explode($sep,$csv);
  $ncsv=array();
  foreach ($arr as $x){
    array_push ($ncsv, $pre . $x . $pst);
  }
  return implode($resep,$ncsv);
}

/*
==========================================================================================================





              
██████╗ 
██╔══██╗
██║  ██║
██║  ██║
██████╔╝
╚═════╝ 
        
              

************************************************************************************************
             /<                                             
            /<                                              
  |\_______{o}----------------------------------------------------------_
 [\\\\\\\\\\\{*}:::<===================  DATA    =================-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/


function WhosKnock(){ //Return the visiting user as array (Person[UserId], ULevel(User Level), UGroup [UserGroup])
  $Person = "Guest";
  $ULevel = 0;
  $UGroup = "Guest";
  if (!empty($_SESSION['Person'])){ 
    $Person = $_SESSION['Person'];
    $ULevel = $_SESSION['UserLevel'];
    $UGroup = $_SESSION['UserGroup'];
  }
  return array ("Person"=>$Person,"ULevel"=>$ULevel,"UGroup"=>$Group);
  
}

//Make <option> from list_list (List Cluster,Class connecting to List table)
function DrawOption($cluster,$ListDB=null,$orderby="ASC",$CustomDBVal=null,$CustomDBOpt=null,$CustomDBWhr=null){
  //(List's cluster (Default Table), GoodBoi class to access lis_list or custom table[default is list_list],Column as Value, Column as Option, Where condition for custom table)
  $ListDB= empty($ListDB)? new GoodBoi("list_list") : $ListDB;
  switch($orderby){
    case "ASC":
      $orderby=" list_order ASC";
    break;
    case "DESC":
      $orderby=" list_order DESC";
    break;
  }
  $cluster= empty($CustomDBWhr)? " cluster = '". $cluster ."' AND active <> 0" : " $CustomDBWhr "; 
  $Val= empty($CustomDBVal)? 'list_value'  : $CustomDBVal; 
  $Opt= empty($CustomDBOpt)? 'list_name' : $CustomDBOpt; 
  $Selection=$ListDB-> GoFetch("WHERE $cluster"); //Chose list in certain cluster which active (active not null)
  
    foreach($Selection as $L){
      if(!empty($L['default'])){ $Default="selected=selected"; }
      $Options = $Options ."<option value=". $L[$Val] ." $Default>". lan2var($L[$Opt]) ."</option>";
    }
  return $Options;
}



/*
==========================================================================================================







************************************************************************************************
             /<                                             
            /<                                              
  |\_______{o}----------------------------------------------------------_
 [\\\\\\\\\\\{*}:::<===================  DEBUG  =================-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/


function DeFlea($a,$Identifier,$Header,$post){ 
  if(!empty($_SESSION['DeFlea'])){ 
    echo "<br><p class=DeFlea><strong>$Identifier</strong><br><i>$Header</i><br>";
    if(is_array($a)){
      print_r($a);
    } else {
      echo $a ;
    }
    echo " $post on line <b>". __LINE__ ."</b></p>";
  }
}

/*
==========================================================================================================







************************************************************************************************
             /<                                             
            /<                                              
  |\_______{o}----------------------------------------------------------_
 [\\\\\\\\\\\{*}:::<===================  DISPLAY  ===============-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/

function ErrorDialog($Title,$Content){
  Global $lanError;
  echo "<table class=ErrorDialog><th>$lanError - $Title</th><tr><td>$Content</td></tr></table><br>";
   
}

function OkDialog($Title,$Content){
  Global $lanOK;
  echo "<table class=OkDialog><th>$lanOK - $Title</th><tr><td>$Content</td></tr></table><br>";
   
}

function WarningDialog($Title,$Content){
  Global $lanWarning;
  echo "<table class=WarningDialog><th>$lanWarning - $Title</th><tr><td>$Content</td></tr></table><br>";
   
}

function Mark($a,$pre=null,$post=null){ //DEBUGGING TOOLS
  echo "<br><p class=Mark><strong>$pre </strong>";
  if(is_array($a)){
    print_r($a);
  } else {
    echo $a ;
  }
  echo " $post</p>";
}

function LastSQLEntry($table,$short,$number=10,$print=1){
  $DB = new GoodBoi($table);
  $Row = $DB->GoFetch("ORDER BY ". $short ." DESC LIMIT ". $number);
  if(!empty($print)){
    $Header=1;
    echo "<table>";
    foreach($Row as $x){
      $isi=array2csv($x,$bra=null,$sep="<td>");
      if(!empty($Header)) { 
        echo "<tr>". $isi['Key'] ."</tr>";
        unset($Header);
      }
      echo "<tr>". $isi['Val'] ."</tr>";
    }
    echo "</table>";
  }
  return $Row;
}

function ZebraList($Array,$NameColumn,$Label=null,$Argument=array('Listing','Keroro','Dororo','Kururu','Name',null,null)){
  
  //Variabelize Arguments
    $StyleList= empty($Argument[0])? 'Listing' : $Argument[0]; // CSS Style for list table
    $Style1= empty($Argument[1])? 'Keroro' : $Argument[1]; //CSS class for row Odd
    $Style2= empty($Argument[2])? 'Dororo' : $Argument[2];// CSS class for row Even
    $StyleHeader= empty($Argument[3])? 'Kururu' : $Argument[3];// CSS class for header
    $NameLabel= empty($Argument[4])? $GLOBALS['lanName'] : $Argument[4]; // Label for 'Name' Column
    $URI=$Argument[5]; // GET
    $DID=$Argument[6]; // Data ID column (For linking)

  foreach($Array as $y=>$x){
    $RID=$x[$DID];
    if(Empty($Zebra)){
      $Zebra = array('pre'=>"<table class=$StyleList><tr class=$StyleHeader>");
      foreach ($x as $b=>$a){
        if(in_array($b,$NameColumn)){ // If the key is included in the [Name Column], Use $NameLabel as header
          if(empty($HCounter)){ $Zebra['pre'] .="<th>$NameLabel</th>"; $HCounter++; }
        } else {
          $b= lan2var($Label[$b] );
           $Zebra['pre'] .="<th>". $b ."</th>";
        }
      }     
      unset($HCounter);
    }
    if($y%2==0){ $Style=$Style1; } else { $Style=$Style2; } 
    $Zebra += array($RID=>"<tr class=$Style>");
    $URI=$_GET;
    $URI['job']=3;
    $URI= GetGET($URI);
    foreach ($x as $b=>$a){
      if(in_array($b,$NameColumn)){ // If the key is included in the [Name Column], join them
        if(empty($HCounter)){ $Zebra[$RID] .= "<td><a href=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?$URI". "dataid=". $RID ." >"; $HCounter++; }
        $a= lan2var($a);
        $Zebra[$RID] .=  "$a ";
      } else {
        $a= lan2var($a);
        $Zebra[$RID] .= "<td>$a</td>";
      }
    }
    unset($HCounter);
  }
  $Zebra['post'] = "</table>";
  //DeFlea($Zebra, __FUNCTION__);
  return $Zebra;
}


//---------------The Main Drawer---------------------------
//$Draw can be array or string
function Gardevoir($Draw){
    if(is_array($Draw)){
    foreach($Draw as $x){
      Gardevoir($x);
    }
  } else {
    echo "<p>". htmlspecialchars($Draw) ."</p>";
    echo $Draw;
  }
}


/*
==========================================================================================================




           
           
           
  ███████╗
██╔════╝
█████╗  
██╔══╝  
██║     
╚═╝     
        
                         
                         
                         
                         


************************************************************************************************
             /<                                             
            /<                                              
  |\_______{o}----------------------------------------------------------_
 [\\\\\\\\\\\{*}:::<==============  FILTERING & VALIDATION=======-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/
// Validating input data

function ValidateField($Field,$Rule=null){ 
  global $FieldValidaiton;
  if(empty($Rule)){ $Rule=$FieldValidaiton ; }
   //Debug
   if(!empty($_SESSION['DeFlea'])){ Mark("Field : $Field || Rule : $Rule || Result". preg_match($Rule,$Field)); } 
   //Debug
  return preg_match($Rule,$Field);
}

function ValidateEmail($Field){
  $Vemail = filter_var($email, FILTER_VALIDATE_EMAIL) ? 0 : 1;
   //Debug
   if(!empty($_SESSION['DeFlea'])){ Mark("Field : $Field is $Vemail"); } 
   //Debug
  return $Vemail;
}


//Creating new list in list_list if datalist input is new data 

function AddDatalist($input,$cluster,$LastOrder=0,$position="Last"){ 
  $akamaru=new GoodBoi("list_list");
  if(!$akamaru->GoCount("WHERE cluster='". $cluster ."' AND list_value='". $input ."'")){
    switch ($position){
      case "Before":
        $Order=$LastOrder-1;
        break;
      case "First":
        $Order=1;
        break;
      default:
        $Order=$LastOrder+1;
        break;
    }
    $akamaru->GoBark(array("cluster"=>$cluster,"list_value"=>$input,"list_name"=>$input, "list_order"=>$Order));
  }
}

//Check wether data is a result of serialisasion and unzerilasisation them
// (String to be deserilazation, want to turn into list?,array for list option(KeySeperator,Pre,InPre,InPos,Post Text))
function deserialization($str,$listka,$list=null){
  $data = @unserialize($str);
  if ($str === 'b:0;' || $data !== false) { 
    if(!empty($listka)){
      $data=Array2List($data,$list[0],$list[1],$list[2],$list[3],$list[4],$list[5]);    
    }
    return $data;
  } else {
      return $str;
  }
}


//////////////www.catswhocode.com/blog/10-awesome-php-functions-and-snippets////////////
function StripTag($input) {

  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );

    $output = preg_replace($search, '', $input);
    return $output;
  }

  //===================================================================================

////////////////http://blog.koonk.com/2015/07/46-useful-php-code-snippets-that-can-help-you-with-your-php-projects/////
  function clean($input)
   {
    if (is_array($input))   
    {   
      foreach ($input as $key => $val)   
       {   
        $output[$key] = clean($val);   
        // $output[$key] = $this->clean($val);   
      }   
    }   
    else   
    {   
      $output = (string) $input;   
      // if magic quotes is on then use strip slashes   
      if (get_magic_quotes_gpc())    
      {   
        $output = stripslashes($output);   
      }   
      // $output = strip_tags($output);   
      $output = htmlentities($output, ENT_QUOTES, 'UTF-8');   
    }   
  // return the clean text   
    return $output;
  }   
  
 //===================================================================================


function Antiseptic($input){
  $input=StripTag($input);
  $input=clean($input);
  return $input;
}
/*
==========================================================================================================








██╗     
██║     
██║     
██║     
███████╗
╚══════╝
        


************************************************************************************************
             /<                                             
            /<                                              
  |\_______{o}----------------------------------------------------------_
 [\\\\\\\\\\\{*}:::<=================== LOGGING  ================-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/

function WhoAreYou(){ // Extract some information from $_SERVER
  return array('Browser'=>$_SERVER['HTTP_USER_AGENT'],'IP'=>$_SERVER['REMOTE_ADDR'],'Port'=>$_SERVER['REMOTE_PORT'],'URI'=>$_SERVER['REQUEST_URI'],'sIP'=>$_SERVER['SERVER_ADDR'],'sPort'=>$_SERVER['SERVER_PORT']);
}
class Kayu extends GoodBoi{
  
  function __Construct(){
    global $dbpre;
    $this->table=$dbpre ."Kayu";
  }

  function KayuManis($a,$b,$d){ /// Logging Function | $a=Short Description | $b=Command/Packet/Content | $c=Succes? (true/false) | $d=Error
    WhosKnock();
    $l= WhoAreYou();
    $ULevel= empty($_SESSION['ULevel'])? 0 : $_SESSION['ULevel'];
    $Kayuing=array('SDesc'=>$a , 'Culpirt'=>$l['IP'], 'CBrowser'=>$l['Browser'], 'CPort'=>$l['Port'], 'Victim'=>$l['sIP'], 'Vport'=>$l['sPort'], 'VURI'=>$l['URI'], 'Command'=>$b, 'Error'=>$d, 'User'=>$_SESSION['Person'], 'UserN'=>$_SESSION['UserN'], 'Name'=>$_SESSION['Name'], 'ULevel'=>$ULevel, 'UGroup'=>serialize($_SESSION['UGroup'])); 
    $this->GoBark($Kayuing);
    //Debug
   if(!empty($_SESSION['DeFlea'])){ Mark($Kayuing,"LOG || "); } 
   //Debug
  }
}

 
/*
==========================================================================================================









███╗   ███╗
████╗ ████║
██╔████╔██║
██║╚██╔╝██║
██║ ╚═╝ ██║
╚═╝     ╚═╝
           

************************************************************************************************
             /<                                             
            /<                                              
  |\_______{o}----------------------------------------------------------_
 [\\\\\\\\\\\{*}:::<===========  MySQL Related  =================-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/

//----------Close DB--------
function whine() {
    $ewe->close();
    
}

//---------Connect DB------------
//SniffButt(Database (0 for default))
function SniffButt($DBase=null, $Server=null){
  global $MRdb, $MRhoster, $MRperson, $MRlock, $DBase;
  $Server = isset($Server) ? $Server : array("Host"=>$MRhoster, "Person"=>$MRperson, "Lock"=>$MRlock);
  $DBase = isset($DBase) ? $DBase : $MRdb;
  
  /*
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
*/
  return new mysqli($Server['Host'], $Server['Person'], $Server['Lock'], $DBase);
}

/*
==========================================================================================================







██╗   ██╗
██║   ██║
██║   ██║
██║   ██║
╚██████╔╝
 ╚═════╝ 
         



************************************************************************************************
             /<                                             
            /<                                              
  |\_______{o}----------------------------------------------------------_
 [\\\\\\\\\\\{*}:::<===================  User    =================-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/


//-------------------User-----------------------
function Login($User){
  $userlist=new GoodBoi("staff_list");
  $x=$userlist->GoFetch("WHERE usrid='$User'");
  $_SESSION['Person']=$User;
  $_SESSION['UserN']=$x[0]['UserName'];
  $_SESSION['Name']= $x[0]['FName'] ."/". $x[0]['MName'] ."/". $x[0]['LName'];
  $_SESSION['ULevel']=$x[0]['UserLevel'];
  $_SESSION['UGroup']=deserialization($x[0]['UserGroup']);
}

function Logout(){
  unset($_SESSION['Person']);
  unset($_SESSION['UserN']);
  unset($_SESSION['Name']);
  unset($_SESSION['ULevel']);
  unset($_SESSION['UGroup']);
}

function LogUser($Userclass=null){ // Update the Last IP and Last activity of user (User ID, Class that linked to staff_list db)
  if(empty($_SESSION['Person'])) { return; }
	$Who=WhoAreYou();
	$Who=serialize($Who);
  $Updatecol=array("LastActiveTime"=>Date2SQL(), "LastActiveIP"=>$_SERVER['REMOTE_ADDR'], "LastActiveInfo"=>$Who);
  if(empty($Userclass)){ $Userclass= new GoodBoi("staff_list"); } // If $Userclass empty, make a new class to link to staff_list db
  $Userclass->GoBurry($Updatecol,"WHERE usrid=". $_SESSION['Person']);
}


// Function to check user permission based on usergroup, level, and specifi user. Only one statement needed to be true. (Permission will be denied regardless if user belong to banned groups, and granted regardless if user is Super Admin)
// (group(array of allowed usergroup), level(integer of minimum user level allowed), user(array of specific user allowed),Unathorized access Messag Title, Unauthorized access message Content, Unauthorized access message type [Ok,Warning,Error])
function Bouncer($group=array("user"),$level=1,$person=0,$MessageT=null,$MessageC=null,$Box='Error'){
  
  //Grant access to Super Admin
  if(in_array($_SESSION['UGroup'],$GLOBALS['SettingSuperAdminGroup'])  || $_SESSION['ULevel'] >= $GLOBALS['SettingSuperAdminLevel']){ return TRUE; }


  //Denny access when user is banned
  if(in_array($_SESSION['UGroup'],$GLOBALS['SettingBannedGroup'])){ ErrorDialog($GLOBALS['lanBannedT'],$GLOBALS['lanBannedC']); return FALSE; }


  if(in_array($_SESSION['UGroup'],$group)){ return TRUE; } // Check for User Group
  if($_SESSION['ULevel']>=$level){ return TRUE; } // Check for User Level
  if(in_array($_SESSION['Person'],$person)){ return TRUE; } // Check for Specific User
  $MessageC= empty($MessageC)? $GLOBALS['lanBouncerC'] : $MessageC;
  $MessageT= empty($MessageT)? $GLOBALS['lanBouncerT'] : $MessageT;
  switch($Box){
    case 'Error':
      ErrorDialog($MessageT,$MessageC);
      break;
    case 'Warning':
      WarningDialog($MessageT,$MessageC);
      break;
    case 'OK':
      OKDialog($MessageT,$MessageC);
      break;
  }
  $GLOBALS['LogDes']="Unauthorized Access";
  $GLOBALS['ErrorLog']="User Group is ". array2csv($_SESSION['UGroup']) .", allowed user group are". array2csv($group) ." || User level is ". $_SESSION['ULevel'] .", allowed Level is past $level";
  return FALSE;
}

/*
************************************************************************************************
             /<                                             
            /<                                              
  |\_______{o}----------------------------------------------------------_
 [\\\\\\\\\\\{*}:::<===================  URI    =================-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/

//---Get the GET variable and return as string {For making certain URL}
function GetGET($Arr=null){
  if(empty($Arr)){ $Arr=$_GET; }
  foreach($Arr as $x=>$y){
    $URI=$URI. "$x=$y&";
  }
  return $URI;
}













































/*
==============================================================================================================
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
==============================================================================================================
 ██████╗██╗      █████╗ ███████╗███████╗
██╔════╝██║     ██╔══██╗██╔════╝██╔════╝
██║     ██║     ███████║███████╗███████╗
██║     ██║     ██╔══██║╚════██║╚════██║
╚██████╗███████╗██║  ██║███████║███████║
 ╚═════╝╚══════╝╚═╝  ╚═╝╚══════╝╚══════╝
                                        
==============================================================================================================
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
==============================================================================================================
*/

/*

==============================================================================================================  
 ██████╗  ██████╗  ██████╗ ██████╗ ██████╗  ██████╗ ██╗
██╔════╝ ██╔═══██╗██╔═══██╗██╔══██╗██╔══██╗██╔═══██╗██║
██║  ███╗██║   ██║██║   ██║██║  ██║██████╔╝██║   ██║██║
██║   ██║██║   ██║██║   ██║██║  ██║██╔══██╗██║   ██║██║
╚██████╔╝╚██████╔╝╚██████╔╝██████╔╝██████╔╝╚██████╔╝██║
 ╚═════╝  ╚═════╝  ╚═════╝ ╚═════╝ ╚═════╝  ╚═════╝ ╚═╝
                                                       
==============================================================================================================



MySQL query
To construct: $var = GoodBoi (Table)
Method to call: 
Select: GoFetch(MySQL Script after FROM,[Selection, default is "*" (all)])
Insert: GoBark(MySQL Script after FROM)
Count Row: GoCount(MySQL Script after FROM)

////////////////////////////////////////////////////////////////////*/

class GoodBoi{
  protected $table;

  public function __Construct ($table,$pre=null){
    global $dbpre;
    if(empty($pre)){
      $pre=$dbpre;
    } 
    $this->table=$pre.$table;
  }

  
  public function GoFetch ($selection,$Method="*"){ // Method to SELECT database 
    $ewe = SniffButt();
    $query="SELECT ". $Method ." FROM ". $this->table ." $selection";
    //Debug
    if(!empty($_SESSION['DeFlea'])){ Mark($query,__CLASS__ ." ==> ". __FUNCTION__); echo debug_backtrace()[1]['function']; }
    //Debug
    $result = $ewe->query($query);
   $row = $result->fetch_all(MYSQLI_ASSOC); 

   return $row;
  }

  public function GoBark ($data){ // Method to INSERT data, $data is an array (Column as Key, and Values as Value))
    $bun = SniffButt();
    $dataesc= Blissey($data);
    $filling=array2csv($dataesc,"'",null,1);
    $sausage= "INSERT INTO ". $this->table ." (". $filling['Key'] .") VALUES (". $filling['Val'] ."); ";
    //Debug
    DeFlea($sausage, __CLASS__ . "++++" . __FUNCTION__);
   //Debug
    $bun->query($sausage) ;
    DeFlea($bun->error, "Wan! Wan! Wan!");
    return $bun->insert_id;
  }
  public function GoCount($a){
    $RowCount=$this->GoFetch($a,"COUNT(*) as Total");
    //Debug
    
   if(!empty($_SESSION['DeFlea'])){ Mark("Row Count is : [". $RowCount[0]['Total'] ."] for $a"); } 
   //Debug
      
   return $RowCount[0]['Total'];
  }

  public function GoBurry($data,$selection){ //Method to Update SQL data (array(column=>new value,"WHERE bla bla bla"array(column=>new value))
    $bun = SniffButt();
    $dataesc= Blissey($data);
    $filling=array2arrow($dataesc,"="," , ");
    $sausage= "UPDATE ". $this->table ." SET $filling $selection";
   mark($sausage,"[","]");
    //Debug
   //Debug
   DeFlea($sausage, __CLASS__ . "++++" . __FUNCTION__);
   //Debug
   if ($bun->query($sausage) === TRUE) {
    return $bun->insert_id;
  } else {
    return $bun->error;
  }
   //Debug

  
   
   

  }

}
/////////////////////////////////////////////////////////////////



















/*

==============================================================================================================   
███████╗███╗   ███╗███████╗ █████╗ ██████╗  ██████╗ ██╗     ███████╗
██╔════╝████╗ ████║██╔════╝██╔══██╗██╔══██╗██╔════╝ ██║     ██╔════╝
███████╗██╔████╔██║█████╗  ███████║██████╔╝██║  ███╗██║     █████╗  
╚════██║██║╚██╔╝██║██╔══╝  ██╔══██║██╔══██╗██║   ██║██║     ██╔══╝  
███████║██║ ╚═╝ ██║███████╗██║  ██║██║  ██║╚██████╔╝███████╗███████╗
╚══════╝╚═╝     ╚═╝╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚══════╝
                                                                    
==============================================================================================================



Displaying Layout
////////////////////////////////////////////////////////////////////*/



class Smeargle{
  protected $ViewMode; //----Which viewing mode ? "view", "new" or "edit"
  protected $Layout; //----The Form Id
  protected $admin; //-----admin access?
  protected $Style; //---Style CSS
  protected $MySQL_Object; //---Class that link to layout table
  protected $MySQL_Data; //---Class that link to Data table
  protected $Data; //---User ID Edited
  protected $CustomList; //---Custom list
  protected $UserData;
  protected $DataID;

  function __Construct($form,$view,$DataID,$mysqlclass=null,$datadbclass=null,$Data=null,$adminlevel=69,$style="layout_form"){ // (Form id, Viewing Mode (Edit, View), Class that handle linking to MySQL DB if available (if leave blank, this calss will make it's own conenction), same as previous, but this link to staff_list table, Minimal User Level to access admin-only-content, css style)
    if(!empty($_SESSION['DeFlea'])){ mark("Drawed", __CLASS__ ." ----> ". __FUNCTION__ ."   "); } //DEBUG
    $this->DataID=$DataID;
    $this->ViewMode=$view;
    $this->Layout=$form;
    $this->adminlevel=$adminlevel;
    $this->Style=$style;
    $this->Data= empty($_GET['dataid'])? $_SESSION['Person']: $_GET['dataid']; //If No Specific user, get own instead
    //Make new class to link to layout table
    if(empty($mysqlclass)){ 
      $mysqlclass= new GoodBoi("layout"); 
      if(!empty($_SESSION['DeFlea'])){ mark("Linking to GoodBoi Layout"); } }
    //Make new class to link to staff_list table
    if(empty($datadbclass)){ 
      $datadbclass= new GoodBoi("staff_list"); 
      if(!empty($_SESSION['DeFlea'])){ mark("Linking to GoodBoi Layout"); } }
    $this->MySQL_Object=$mysqlclass;
    $this->MySQL_Data=$datadbclass;
    if($_SESSION['ULevel']>=$adminlevel){ $this->admin=" || field_visible_admin !=0"; } //If admin level is high enough, grant admin 
  }

  // Function for drawing: Work by putting the HTML code to a variable $Draw, and append ne HTML code to the variable each time it make new code, an then returned it as return, so it can be echoed with the caller to really draw the form (Use $Resultstring=str_replace('$lan',"$lan",$Resultstring) to convert all the 'turned to string' $lan... to proper variable $lan...)
  function DrawForm($Button=null,$method="POST"){
    //Button is array (Submit Edit,Cancel,Edit,Submit New)
   
    //Debug
    if(!empty($_SESSION['DeFlea'])){ mark("Drawed", __CLASS__ ." ----> ". __FUNCTION__ ."   "); }
   
    $URI = GetGET();
    //Draw form opening ( <form> and <table>)
    $Draw = "<form action=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?$URI method=$method class=". $this->Style ."><table class=". $this->Style ."><tr>"; 
    
    //---Draw form
   $Draw .= $this->Grouping(); //  Go to function Grouping to draw each group
   
   //Setting default values of buttons depending on languages
   global $lanSubmit;
   global $lanEdit;
   global $lanReset;
   if(empty($Button[0])){ $Button[0]=$lanSubmit;}
   if(empty($Button[1])){ $Button[1]=$lanReset;}
   if(empty($Button[2])){ $Button[2]=$lanEdit;}
   if(empty($Button[3])){ $Button[3]=$lanSubmit;}
   //Drawing button for each viewing type
   switch ($this->ViewMode){
     case "new":
      $Draw .= "<tr><td colspan=2><input type=Submit value=$Button[0]></input> <input type=reset value=$Button[1]></input>";
      break;
    case "edit":
     $Draw .= "<tr><td colspan=2>
                        <input type=hidden name=". $this->DataID ." value=". $this->UserData[$this->DataID] .">
                        <input type=Submit value=$Button[2]></input> <input type=reset value=$Button[1]></input>"; 
      break;
    case "view":
      $Draw .= "<tr><td colspan=2><a href=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_login&job=4>$lanEdit</a>";
      break;
   }

   //Draw closur </table> and </form>"
   $Draw .= "</table></form>";
   return $Draw;
  }

  function Grouping($style="layout_group"){
    // Fetching DB from layout table
    $Group=$this->MySQL_Object-> GoFetch("WHERE form_id = '". $this->Layout ."' ORDER BY group_order",'DISTINCT group_order, group_cap');
    


    //Drawing group
    foreach($Group as $g){
      $Draw= $Draw . "<tr><td><table class=$style>";
        $Draw= $Draw . $this->Fielding($g['group_cap']); //Go to Fielding function to draw field
      $Draw= $Draw . "</table></td></tr>";
    }
    return $Draw;
  }

  function Fielding($Group){
    $View="field_visible_". $this->ViewMode;
    mark($View,"View Mode");
    $Field=$this->MySQL_Object-> GoFetch("WHERE form_id = '". $this->Layout ."' AND group_cap='". $Group ."' AND ($View <> 0 $admin) ORDER BY field_order"); // Fetching from MySQL, the field with the same Group ID which is visible

    switch($this->ViewMode){
      case "edit":
        $UserData=$this->MySQL_Data-> GoFetch("WHERE ". $this->DataID ." = '". $this->Data ."'" );
        $this->UserData=$UserData[0];
      case "new":
        $Draw=$Draw . $this->NewFielding($Field,$Group);
        break;
      case "view":
        $Draw=$Draw . $this->ViewFielding($Field,$Group);
        break;
    }
    return $Draw;
  }


  // Draw field if viewing mode is Edit or New
  function NewFielding($Field,$Group){
    
    //Make connection to list_list/custom list table for use latter
    $List=new GoodBoi("list_list");
    //custom list
    $Custom_List_Class=$this->MySQL_Object-> GoFetch("WHERE form_id = '". $this->Layout ."' AND (field_list_table IS NOT NULL OR field_list_table <> 0)",'DISTINCT field_list_table');
    $this->CustomList=array();
    foreach($Custom_List_Class as $x){
      if(!empty($x['field_list_table'])){
        DeFlea($x, __CLASS__ ." ". __FUNCTION__, __LINE__);
        array_push($this->CustomList,$x['field_list_table']);
      }
    }


    foreach($Field as $F){ // Script for each found filed
      DeFlea($F['field_label']);
      
      // Assign attributes
      $minlength= empty($F['field_minlength']) ? "" : "minlength=". $F['field_minlength'] ;
			$placeholder= empty($F['field_placeholder'] ) ? "" : "placeholder=". $F['field_placeholder'] ;
      $validator= empty($F['field_validation'] ) ? "" : "pattern=". $F['field_validation'] ;
      $required= empty($F['required'] ) ? "" : "required" ;

      switch($this->ViewMode){
        case "new":
          $defaultvalue = empty($F['field_default']) ? "" : "value=". $F['field_default'] ;
          break;
        case "edit":
          $defaultvalue = empty($this->UserData[$F['field_id']]) ? "" : "value=". $this->UserData[$F['field_id']] ;
          break;
      }
     
      switch($F['field_type']){
        case "select":
        case "datalist":
          if(in_array($F['field_list_table'],$this->CustomList)){ 
            $ListX=new GoodBoi($F['field_list_table']); 
            $CustomDBVal=$F['field_list_value']; 
            $CustomDBOpt=$F['field_list_option']; 
            $CustomOrder=$F['field_order_by']; 
            $CustomDBWhr=$F['field_custom_where'];
            $Options=DrawOption($F['field_list'],$ListX, $CustomOrder, $CustomDBVal, $CustomDBOpt, $CustomDBWhr);
            unset($CustomDBOpt,$CustomDBVal,$CustomDBWhr,$CustomOrder);
          } else {
            $Options=DrawOption($F['field_list'],$List);
          }
        }
      
       
      // Determining input type, and how to draw them
      //function DrawOption($cluster,$ListDB=null,$orderby="ASC",$CustomDBVal=null,$CustomDBOpt=null,$CustomDBWhr=null){
      switch($F['field_type']){
        case "select":
          $Input="select";
          $Close="select";
          $InputContent = $Options;
          break;
        case "datalist":
          $Input="input $required list=". $F['field_id'];
          $Close="input";
          $InputContent = "<datalist id=". $F['field_id'] .">". $Options ."</datalist>";
          break;
        case "password":
          $Input="input type=password $required";
          $Close="input";
          if($this->ViewMode=="edit"){
            $Pre= "<tr><td>". $GLOBALS['lanOldPass']. "<td><input type=password name=Exc-". $F['field_id'] . "Old></input>" ;
          }
          $Additional="<tr><td>". $GLOBALS['lanConfirmPass'] ."<td><input type=password name=Exc-".  $F['field_id'] ."Conf></input>";
          break;
        default:
          $Input="input type=". $F['field_type'] ." $defaultvalue $placeholder $minlength $required";
          $Close="input";
          break;
      }

      //If ciled label contain $lan, get that into variable
      $F['field_label'] = lan2var($F['field_label']);
     

      //The main drawing script
      if(empty($Draw)){ $Draw= "<tr><th colspan=2>". lan2var($Group) ."</th></tr>"; } //Group Header (Drawed only when at least one element showed, and only once in each group)
      
      $Draw= $Draw . "$Pre
                      <tr>
                        <td>
                          <label for=". $F['field_id'] .">". $F['field_label'] ."</label>
                        </td>
                        <td>
                          <$Input name=".  $F['field_id'] .">
                            $InputContent
                          </$Close>     
                        </td>
                      </tr>
                      $Additional
                      "; // Input
                      unset($InputContent);
                      unset($Additional);
                      unset($Pre);
                    }
                    
                    return $Draw;
                    
  }

  // Draw field if viewing mode is View
  function ViewFielding($Field,$Group){
    $Staff=$this->MySQL_Data->GoFetch("WHERE ". $this->DataID ." = '". $this->Data ."'");
    foreach($Field as $F){ // Script for each found filed
      
      //Check if content is an Array
        $FLabel=deserialization(lan2var($Staff[0][$F['field_id']]),1,array(":"));
      

      if(empty($Draw)){ $Draw= "<tr><th colspan=2>". lan2var($Group) ."</th></tr>"; } //Group Header (Drawed only when at least one element showed, and only once in each group)
      $Draw= $Draw . "<tr>
                        <td>
                          ". lan2var($F['field_label']) ."
                        </td>
                        <td>
                          ". $FLabel ."
                        </td>
                      </tr>"; // Input
    }
    return $Draw;
  }
}

/*
  ===============================================================================================
  ███████╗    ██╗   ██╗ █████╗ ██╗     
██╔════╝    ██║   ██║██╔══██╗██║     
█████╗      ██║   ██║███████║██║     
██╔══╝      ╚██╗ ██╔╝██╔══██║██║     
██║██╗       ╚████╔╝ ██║  ██║███████╗
╚═╝╚═╝        ╚═══╝  ╚═╝  ╚═╝╚══════╝
                                     
	===============================================================================================
	*/ 
  class FieldValidation{
    protected $EM;
    public $SignUpError; //Array to store sign up error
    protected $MyClass; // A GoodBoi class for layout
    protected $MyClasStaff; // A GoodBoi class for staff
    protected $FormId; // Form Id that validate (Form id where the field POST-ed) came from
    public $Error1; // Array of field(s) whom didn't pass Error1 check (Empty value on required field)
    public $Error2; // Array of field(s) whom didn't pass Error2 check (Duplicate on Unique field)
    public $Error3; // Array of field(s) whom didn't pass Error3 check (Invalid character)
    public $Error4; // Array of field(s) whom didn't pass Error4$5 check (Password Change)
    public $User; 
  
    function __Construct($Error,$FormId,$MyClass=null,$MyStaff=null,$ShowError=1,$E0=null,$E4=null,$E1=1,$E2=1,$E3=1){
      // (Error language variable, Form ID, GoodBoi class layout, staff, Display error?, Run Check error0 array(Input,Confirmation), error4 (UserID,New Password, Old Password, Password Field ID), error1, error2, error3)
    $this->SignUpError=array(); 
    $this->FormId=$FormId;
    $this->EM=$Error;
    $this->MyClass= empty($MyClass)? new GoodBoi("layout") : $MyClass;
    $this->MyClasStaff= empty($MyStaff)? new GoodBoi("staff_list") : $MyStaff;
    $this->User=$E4[0];
    if (!empty($E0)){ $this->RepeatString($E0[0],$E0[1]); $E0=1; }
    if (!empty($E1)){ $this->Required(); }
    if (!empty($E2)){ $this->Unique(); }
    if (!empty($E3)){ $this->ValidateField(); }
    if (!empty($E4)){ $this->WrongPassword($E4[1],$E4[2],$E4[3]); }
    mark($this->SignUpError,"SIGN ERROR");
    if (!empty($ShowError)){ $this->SummonExodia($E0,$E1,$E2,$E3,$E4); }
    }
    
    
    function RepeatString($confirmated='null',$confirmaiton='null'){
      // ERROR 0 Check!!
      //Check wether password and confirmation password is matched
      // (String to confirm, confirmation)
      if($confirmated != $confirmaiton){
        array_push($this->SignUpError,"Error0") ;
      }
    }
    
    
    function Required(){
      // ERROR 1 CHECK!! 
      //Check if there are Empty value on required field
      $this->Error1 = array();
      $Required=$this->MyClass->GoFetch("WHERE form_id = '". $this->FormId ."' AND required <> 0","field_id, field_label");
      foreach($Required as $y){
        $field=$y['field_id'];
        if(empty($GLOBALS['newera'][$field])){
          array_push($this->Error1,$y['field_label']);
        }
      }
      if(!empty($this->Error1)){ array_push($this->SignUpError,"Error1") ;}
    }
   
    function Unique(){
      // ERROR 2 CHECK!! 
      //Check if someone already registered with same Unique field (eg. Same email)
      $this->Error2=array();
      $unique = $this->MyClass->GoFetch("WHERE form_id = '". $this->FormId ."' AND field_unique <> 0","field_id, field_label");
      foreach($unique as $y){
        $RowCount = $this->MyClasStaff->GoCount("WHERE ". $y['field_id'] ."='". $GLOBALS['newera'][$y['field_id']] ."'");
        if(!empty($RowCount)){
          $UQ=array($y['field_id'] => $GLOBALS['newera'][$y['field_id']]);
          $this->Error2=$this->Error2+$UQ;
          array_push($this->SignUpError,"Error2"); 
        }
      }
    }
  
    
    function ValidateField(){
      // ERROR 3 CHECK!!
      //Validation chaarcter check
      $this->Error3=array();
      $Validation=$this->MyClass->GoFetch("WHERE form_id = 'gita_login_signup' ","field_id, field_label, field_type, field_validation");
      foreach($Validation as $x){ // For each submited field, check what field type, and validate appropriatly
        $Filed2Validate=$GLOBALS['newera'][$x['field_id']];
        switch($x['field_type']){
          case "email":
            if(!ValidateEmail($Filed2Validate)){;
              $InvField2Push=array($x['field_label']=>$Filed2Validate) ;
              $this->Error3 = $this->Error3 + $InvField2Push; 
            } 
            break;
          case "text":
            if(empty($x['field_validation'])) { continue; }
            if(!ValidateField($Filed2Validate,"/^". $x['field_validation'] ."*$/")){
              $InvField2Push=array($x['field_label']=>$Filed2Validate) ;
              $this->Error3 = $this->Error3 + $InvField2Push; 
            } 
            break;
          }     
      }
      if(!empty($this->Error3)){ array_push($this->SignUpError,"Error3"); }
    }
  
    function WrongPassword($NewPassword,$OldPassword,$PassField){
      // ERROR 4 & 5 CHECK!!
      //Wether the old password entered is correct (For password change), And if the old and new password is the same
      $Oldhash=$this->MyClasStaff->GoFetch("WHERE usrid='". $this->User ."'");
      if(!password_verify($OldPassword,$Oldhash[0][$PassField])){
        array_push($this->SignUpError,"Error4");
        $this->Error4=$PassField;
      }  
      if(password_verify($NewPassword,$Oldhash[0][$PassField])){
        array_push($this->SignUpError,"Error5");
        $this->Error5=$PassField;
      }
    }

  function SummonExodia($E0=1,$E1=1,$E2=1,$E3=1){
    //Summon the ERROR Messeji acording to their error
    //Show Wich Error? (erorr0, 1, 2, 3) Default is 1 (Showing)
    $LogDes=array();
    $ErrorLog=array();
    $EM = $this->EM;
    if( in_array("Error0",$this->SignUpError) && !empty($E0) ){ //Show error dialog wrong confirmation password
	
      ErrorDialog($GLOBALS['lanSignUpErrorMessage0T'],$GLOBALS['lanSignUpErrorMessage0C']);
      
      //For Logging Purpose
      //Construct the error log content

      array_push($LogDes,"[Password & Confirmation password Missmatch]");
      array_push($ErrorLog,"[Missmatch password]");
    
    }
    
    if( in_array("Error1",$this->SignUpError) && !empty($E1) ){ //Show error dialog urging user to fill required field
      $FieldList=array();
      foreach($this->Error1 as $i){
         $i=lan2var($i);
         array_push($FieldList,$i);
      }
      $lanSignUpErrorMessage1C=Array2List($FieldList, $GLOBALS['lan'. $EM .'ErrorMessage1C'],null,null, $GLOBALS['lan'. $EM .'ErrorMessage1A']); //Turn the error field into list
      ErrorDialog($GLOBALS['lan'. $EM .'ErrorMessage1T'],$lanSignUpErrorMessage1C);
      
      //For Logging Purpose
      //Construct the error log content
      array_push($LogDes,"[Empty required field(s)]");
      $EmpErrorLog=array2csv($FieldList);
      array_push($ErrorLog,"[Empty on : ". $EmpErrorLog['Val'] ."]");
      
    
    }
    
    if( in_array("Error2",$this->SignUpError) && !empty($E2) ){ //Show error dialog urging user use different email
      $dup=array();
      $DupErrorLog=array();
      foreach($this->Error2 as $i=>$x){
         eval("\$i = \"$i\";");
         $Dup2Push=array($i=>$x); 
         $DupErrorLog=$DupErrorLog+$Dup2Push;
         array_push($dup,"$i : $x");
      }
      $lanSignUpErrorMessage2C= Array2List($dup, $GLOBALS['lan'. $EM .'ErrorMessage2C'],null,null,$GLOBALS['lan'. $EM .'ErrorMessage2A']);
      ErrorDialog($GLOBALS['lan'. $EM .'ErrorMessage2T'],$lanSignUpErrorMessage2C);
      mark('lan'. $EM .'ErrorMessage2T',"^&RT&^YKVKY");
      
    
      //For Logging Purpose
      //Construct the error log content
      array_push($LogDes,"[Duplicate Unique field(s)]");
      $DupErrorLog=array2csv($DupErrorLog);
      array_push($ErrorLog,"[Duplicate on : ". $DupErrorLog['Key']. " as " .$DupErrorLog['Val'] ."]");
      
    
    }
    
    if( in_array("Error3",$this->SignUpError) && !empty($E3) ){ //Show error dialog telling user that some field are invalid
      $invali=array();
      $InvErrorLog=array();
      foreach($this->Error3 as $i=>$x){
        $i=lan2var($i);
         $InvError=array($i=>$x); 
         $InvErrorLog=$InvErrorLog+$InvError;
         array_push($invali,$i);
      }
      $lanSignUpErrorMessage3C= Array2List($invali, $GLOBALS['lan'. $EM .'ErrorMessage3C']);
      
      ErrorDialog($GLOBALS['lan'. $EM .'ErrorMessage3T'],$lanSignUpErrorMessage3C);
      
      //For Logging Purpose
      //Construct the error log content
      array_push($LogDes,"[Invalid field(s)]");
      $InvErrorLog=array2arrow($InvErrorLog," filled as ");
      array_push($ErrorLog,"[Invalid on : ". $InvErrorLog ."]");
    }

    if( in_array("Error4",$this->SignUpError) && !empty($E4) ){ //Wrong Password
      
      ErrorDialog($GLOBALS['lan'. $EM .'ErrorMessage4T'],$GLOBALS['lan'. $EM .'ErrorMessage4C'] . $this->Error4);
      
      //For Logging Purpose
      //Construct the error log content
      array_push($LogDes,"[Wrong Password]");
      $InvErrorLog=array2arrow($InvErrorLog," filled as ");
      array_push($ErrorLog,"[Wrong Password on ". $this->Error4 ."]");
    }
    if( in_array("Error5",$this->SignUpError) && !empty($E5) ){ //Wrong Password
      
      ErrorDialog($GLOBALS['lan'. $EM .'ErrorMessage5T'],$GLOBALS['lan'. $EM .'ErrorMessage5C'] . $this->Error4);
      
      //For Logging Purpose
      //Construct the error log content
      array_push($LogDes,"[Same New & Old Password]");
      $InvErrorLog=array2arrow($InvErrorLog," filled as ");
      array_push($ErrorLog,"[Same New & Old Password on". $this->Error4 ."]");
    }
    $GLOBALS['LogDes']=Serialize($LogDes);
    $GLOBALS['ErrorLog']=Serialize($ErrorLog);
  }
  
  }
  

/*
========================================================================================
███████╗███╗   ██╗ ██████╗ ██████╗ ██╗      █████╗ ██╗  ██╗
██╔════╝████╗  ██║██╔═══██╗██╔══██╗██║     ██╔══██╗╚██╗██╔╝
███████╗██╔██╗ ██║██║   ██║██████╔╝██║     ███████║ ╚███╔╝ 
╚════██║██║╚██╗██║██║   ██║██╔══██╗██║     ██╔══██║ ██╔██╗ 
███████║██║ ╚████║╚██████╔╝██║  ██║███████╗██║  ██║██╔╝ ██╗
╚══════╝╚═╝  ╚═══╝ ╚═════╝ ╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝
                                                           
  ====================================================================================
*/

/*
class Snorlax{ //Inputing data into MySQL with version history
  protected $GoodBoi_Version;
  protected $GoodBoi_Target;
  protected $Culpirt;
  protected $Data;
  protected $SnorlaxData;
  protected $TargetName;
  protected $Rule;
  protected $Method;
  protected $CulpirtInfo;
  protected $OldVersion;
  protected $OldData;
  
  function __Construct($Data,$Target,$TargetClass,$Method='edit',$Rule='null'){
    //(Data to be inputed, Target Table DB, Target Class if already declared, Method [new or edit], Rule["x is y"])
   $this->GoodBoi_Version = new GoodBoi("snorlax");
   if(empty($TargetClass)){ $this->GoodBoi_Target = new GoodBoi($Target);  } else {$this->GoodBoi_Target=$TargetClass;  }
   $this->CulpirtInfo = serialize(WhosKnock());
   $this->Culpirt = empty($_SESSION['Person'])? 0 : $_SESSION['Person'] ;
   $this->Target = $Target;
   $this->Rule = $Rule;
   $this->Method = $Method;
   $this->Update = $Update;
   $this->Data = $Data;
   DeFlea($Data, __CLASS__ . "++++" . __FUNCTION__);
   
  switch($Method){
    case 'new':
     $this->NewInput();
      break;
    case 'edit';
    $this->EditInput();
      break;
    }
   
  }

  function NewInput(){
    $DataVersion=array('sversion'=>1);
    $this->Data += $DataVersion;
    $SQuery = $this->FeedSnorlax();
    DeFlea($Data, __CLASS__ . "++++" . __FUNCTION__);
    $this->GoodBoi_Target-> GoBark ($this->Data);
    $this->GoodBoi_Version-> GoBark ($SQuery);
  }

  function EditInput(){
    $SQuery = $this->FeedSnorlax();
    $DataVersion=array($this->OldVersion + 1);
    $Data = $this->Data + $DataVersion;
    $Updated = $this->Rest();
    $this->GoodBoi_Version-> GoBurry ($Updated,"WHERE original_data='". $this->Target ."' AND version='Current'");
    $this->GoodBoi_Target-> GoBurry ($this->Update,$this->Rule);
    $this->GoodBoi_Version-> GoBark ($SQuery);
  }

  function FeedSnorlax(){
    switch($this->Method){
      case 'new': 
        $OriData=0;
        $UptData=serialize($this->Data);
      break;
      case 'edit':
        $OldData=Munchlax();
        $OriData=serialize($OldData);
        $UptData=serialize($this->Update);
      break;
    }
    $SVersion= array('culpirt_info'=>$this->CulpirtInfo,'timest'=>Date2SQL(),'facility'=>$GLOBALS['SettingCurrentFacility'],'original_data'=>$OriData,'edited_data'=>$UptData,'version'=>'Current','original_table'=>$this->Target,'culpirt'=>$this->Culpirt);
    return $SVersion;
  }

  function Munchlax(){
    $OldData=$this->GoodBoi_Target->GoFetch($this->Rule);
    $OriData=array();
    foreach($this->Data as $y=>$x){
      if($x!=$OldData[0][$y]){ // If column data between new and old data is different, made change
        $OriData += $OldData[0][$y];
        $UpdatedData += array($y=>$x);
      }
    $this->OldVersion=$OldData[0]['sversion'];
    $this->Update=$UpdatedData;
    $this->OldData=$OldData;
    return $OriData;
    }
  }

  function Rest(){
    $Prev=$this->GoodBoi_Version->GoFetch("WHERE original_data='". $this->Target ."' AND version='Current'");
    $Updated=array();
    $PechaBerry=unserialize($Prev[0]['edited_data']);
    foreach($PechaBerry as $y=>$x){
      if(!empty($x)){$Updated += array($y=>$this->OldData[$y]); }
    }
    return serialize($Updated);
  }

}


//===========+Class to handle the Version History Controller +================

FLOW
1. Fetch column list with '1' [ColumnList] in Subversion DB where version=current AND edited_id= current edited id (ATR1) AND original_table = edited table (ATR2) [CurentVerRow].

2. Copy value of selected row from original DB in correspondenting column with [ColumnList] to UPDATE [CurentVerRow] and replace the '1' and replace 'current version' with 'version' colomn from origian table.

3. Compare the submited data (ART3) to the data in original DB [ChangedColumn].

4. Make array of with key of [ChangedColumn] and their corespondeting value from submited data, plus change version to the version cureently stored+1, and then UPDATE the original table with those new values

5. INSERT new entry into SVN with '1' in every [CHangedColumn], and version as 'current'
*/

class Snorlax{
  protected $GoodBoi_Version; // class of GoodBoi to snorlax table
  protected $GoodBoi_Target; // class of GoodBoi to [Target] table
  protected $Data; // ARRAY of submited Data as (column=>value)
  protected $TargetID; //string of Target Table's ID column
  protected $TargetTab; //string of Target Table name
  protected $PrevVersionID; // Previous version (Current version before the current update)
  protected $ReturnedVersion; // ARRAY of data from original Table to be writen in Subversion table
  protected $LaxIncense; // ARRAY of additional data to be insterted into the NEW entry of subversion
  protected $OldData; // ARRAY contain the old data from original DB
  protected $NewData; // ARRAY contain the the submited data that different with the old data
   
  function __Construct($DataID,$OriTab,$Data,$Method='Edit',$TargetClass=null){
    //Data ID (e.g. usrid for staff_list), Original Tab (e.g. staff_list), Meyhod (New, Edit), Submited Data in array as column=>value, Class ro connect to target

    DeFlea("SNORLAX");  //========================== DEBUG===========================
   $this->GoodBoi_Version = new GoodBoi("snorlax"); // Connect to snorlax DB
   if(empty($TargetClass)){ $this->GoodBoi_Target = new GoodBoi($Target);  } else {$this->GoodBoi_Target=$TargetClass;  } //connect to target DB
   $CulpirtInfo = serialize(WhosKnock());
   $Culpirt = empty($_SESSION['Person'])? 0 : $_SESSION['Person'] ;
   $this->TargetID = $DataID;
   $this->TargetTab = $OriTab;   
   $this->LaxIncense = array('timest'=>Date2SQL(),'culpirt'=>$Culpirt, 'culpirt_info'=>$CulpirtInfo, 'facility'=>$GLOBALS['SettingCurrentFacility'],'original_table'=>$OriTab, 'edited_id'=>$Data[$DataID]);
   $this->Data = $Data;
   switch ($Method){
     case 'Edit':
      $this->EVs();
      break;
     case 'New';
      $this->IVs();
      break;
   }
  }

  function EVs(){
    DeFlea("SNORLAX EV"); //========================== DEBUG===========================
    //[1]
    $ColumnList=$this->DayCareCouple();
    mark($ColumnList, __FUNCTION__ . "Column List "); //==================================DEBUG=======================
    //[2]
    $Ditto=$this->Ditto($ColumnList);
    mark($Ditto, __FUNCTION__ . "Ditto "); //==================================DEBUG=======================
    //[3]
    $Munchlax=$this->Munchlax();
    mark($Munchlax, __FUNCTION__ . "Munchlax "); //==================================DEBUG=======================
    //[4]
    $this->Snorlax();
    //[5]
    $Snore= $this->Snore($Munchlax);
    mark($Snore, __FUNCTION__ . "Snore List "); //==================================DEBUG=======================
    //GO
    $this->PulverizingPancake($Ditto,$Snore);
    $BURK= new AddList ($this->Data);
  }

  function IVs(){
    DeFlea("SNORLAX IV"); //========================== DEBUG===========================
    /*
    FLOW
    1. Add version=>1 to submited data, insert into target table
    2. Make new array and serialize [DataOne] with key all of the Submited Data Key and value = 1, return as array edited_data=>DataOne, version=>current + Additional data (LaxIncense) and Inster into Subversion table
    
    */
     //[1]
     $Insert = $this->Data + array('sversion'=>1);
     $Pokeball=$this->GoodBoi_Target->GoBark($Insert);
    //[2]
    $DataOne=array();
    foreach($this->Data as $y=>$x){
      $DataOne += array($y=>1); 
    }
    $DataOne= serialize($DataOne);
    $VHC= array('edited_data'=>$DataOne,'version'=>'current','edited_id'=>$Pokeball) + $this->LaxIncense;
    $this->GoodBoi_Version->GoBark($VHC);
    $BURK= new AddList ($this->Data);
  }

  //1. Fetch column list with '1' [ColumnList] in Subversion DB where version=current AND edited_id= current edited id (ATR1) AND original_table = edited table (ATR2) [CurentVerRow].
  function DayCareCouple(){
    $Gluttony= $this->GoodBoi_Version->GoFetch("WHERE version='current' AND edited_id='". $this->Data[$this->TargetID] ."' AND original_table='". $this->TargetTab ."' LIMIT 1" );
    $this->PrevVersionID = $Gluttony[0]['id']; //ID of the Gluttony
    $ColumnList=array(); // set up the ColumnList array
    $SGluttony = unserialize($Gluttony[0]['edited_data']); // Make array of the "edited_data"
    foreach($SGluttony as $y=>$x){ // Assign all the column that is not empty to columnlist
      if(!empty($x)){
        array_push($ColumnList,$y);
      }
    }
    
    return $ColumnList;
  }

  //2. Copy value of selected row from original DB in correspondenting column with [ColumnList] to UPDATE [CurentVerRow] and replace the '1' and replace 'current version' with 'version' colomn from origian table.
  function Ditto($ColumnList){
    $this->OldData= $this->GoodBoi_Target->GoFetch( "WHERE ". $this->TargetID ."='". $this->Data[$this->TargetID] ."'" );
    $this->OldData=$this->OldData[0];
    mark($this->OldData, __FUNCTION__ . "Old Data "); //==================================DEBUG=======================)
    $Returned=array(); // array for the data that copied from original table to subversion table
    foreach($ColumnList as $x){ //Filled $Returned with each ColumnList with data from Original Table
      $Returned += array($x=>$this->OldData[$x]);
    }
    $Returned=serialize($Returned);
    return array('version'=>$this->OldData['sversion']) + array('edited_data'=>$Returned);
  }

  //3. Compare the submited data (ART3) to the data in original DB [ChangedColumn].
  function Munchlax(){
    $ChangedColumn=array();
    $this->NewData=array();
    foreach($this->Data as $y=>$x){
      if($x != $this->OldData[$y]){ //If submited data is different with old data, the different column recorded
        array_push($ChangedColumn,$y); //record the column name contain updated data
        $this->NewData += array($y=>$x); // New data contain only column that different between old data and submited data (in other words, updated data)
      }
    }  
    return $ChangedColumn;  
  }

  //4. Make array of with key of [ChangedColumn] and their corespondeting value from submited data, plus change version to the version cureently stored+1, and then UPDATE the original table with those new values
  function Snorlax(){
    $this->NewData += array('sversion'=>$this->OldData['sversion']+1);
  }

  //5. INSERT new entry into SVN with '1' in every [ChangedColumn], and version as 'current'
  function Snore($ChangedColumn){
    $Snore=array(); //Array to store ChangedColomn=>1  for Inserting into the blah
    foreach($ChangedColumn as $x){ //Store 1 to each changed column
      $Snore += array($x=>1);
    }
    $Snore= serialize($Snore);
    return array('edited_data'=>$Snore,'version'=>'current') + $this->LaxIncense;
  }

  //The actual DB wiritng process
  function PulverizingPancake($Ditto,$Snore){
    $this->GoodBoi_Version->GoBurry($Ditto,"WHERE id='". $this->PrevVersionID . "'"); //[2]
    $this->GoodBoi_Target->GoBurry($this->NewData,"WHERE ". $this->TargetID ."='". $this->Data[$this->TargetID] ."'"); //[4]
    $this->GoodBoi_Version->GoBark($Snore);
  }
}
/*
=======================================================================================
██╗     ██╗███████╗████████╗██╗███╗   ██╗ ██████╗ 
██║     ██║██╔════╝╚══██╔══╝██║████╗  ██║██╔════╝ 
██║     ██║███████╗   ██║   ██║██╔██╗ ██║██║  ███╗
██║     ██║╚════██║   ██║   ██║██║╚██╗██║██║   ██║
███████╗██║███████║   ██║   ██║██║ ╚████║╚██████╔╝
╚══════╝╚═╝╚══════╝   ╚═╝   ╚═╝╚═╝  ╚═══╝ ╚═════╝ 
=======================================================================================
To display a list (e.g. Patient list, Staff list)
                                                  
*/

class Listing{
  protected $GoodBoi; // GoodBoi class
  protected $Query; // ARRAY of fetching data to be listed
  protected $ColumnLabelObj; //class GoodBoi to label name (usually layout)
  protected $Header; //
  protected $ColumnMain;
  protected $DefaultSearchColumn;
  protected $FormID;
  protected $TID;// Main table's ID column
  protected $NameColumn; 
  public $Gardevoir; //Array of the final list draw

  // (GoodBoi Class, [ARGUMENT])
  function __Construct($GoodBoi,$ColumnLabelObj=null,$Argument=array(null,'Listing','*',null,'field_id','field_label',"form_id",null,null,'FName,MName,LName')){
    //Variabelize Arguments
      $Where=$Argument[0]; //WHERE query
      $Style=$Argument[1]; // CSS class
      $ColumnListed=$Argument[2]; // Which column will be listed
      $DefaultOrder=$Argument[3];
      $TID=$Argument[4];
      $HeadingLabel=$Argument[5];
      $HeadingWhereCol=$Argument[6];
      $HeadingWhereVal=$Argument[7];
      $ColumnMain=$Argument[8]; // Which column is the main column (Those will be used as link)
      $DefaultSearchColumn=$Argument[9]; //CSV All column to be searched if no specific column selected
      $NameColumn=$Argument[10]; // Column that makes the name (Also become main column)

      $this->ColumnMain=$ColumnMain;
      $this->Header=$E=Array2Array($ColumnLabelObj->GoFetch("WHERE $HeadingWhereCol ='$HeadingWhereVal'"),'field_id','field_label');
      $this->GoodBoi=$GoodBoi;
      $this->DefaultSearchColumn=$DefaultSearchColumn;
      $this->ColumnLabelObj=$ColumnLabelObj;
      $this->FormID=$HeadingWhereVal;
      $this->TID=$TID;

      //Name Colom to array
      $this->NameColumn=explode(",",$NameColumn);

    //Decide which criteria used to display data (Default if no search criteria inputed from the search bok)
    if(empty($_GET['listcolumn']) || $_GET['listcolumn'] == 'ALL'){ $Src=$DefaultSearchColumn; } else {$Src =$_GET['listcolumn'];} // Turn Argument[9] into array for searching (Use all of them for search criteria)
    if(strpos($Src, ',') !== false ){ // If the Src contain ',', treat explode them into array
      $Src=explode(",",$Src); 
    }
    if(strpos($_GET['listcrit'], 'select__') !== false){ // If search taken from datalist
      $Where = "WHERE ". $this->TID ."='". str_replace("select__","",$_GET['listcrit'])  ."'";
    } else {
      if(!empty($Src)){
        if(is_array($Src)){ // If multiple search column (Treat as array), make multiple search query
          foreach($Src as $x){
            if(!empty($Where)){ $Where .=" OR "; } else { $Where = "WHERE "; }
            $Where .= $x ." LIKE '%". $_GET['listcrit'] ."%' ";
          } 
        } else {
          $Where = "WHERE $Src LIKE '%". $_GET['listcrit'] ."%'";
        }
      }
    }
   
    $this->Query=$GoodBoi->GoFetch($Where,$ColumnListed);
    $this->DrawList();
  }

  function DrawList(){
    $URI = GetGET();
    $Draw=ZebraList($this->Query,$this->NameColumn,$this->Header,array(6=>$this->TID,5=>$URI));
    $this->Gardevoir=$Draw;
    if(Empty($this->Query)){
      echo $GLOBALS['lanNoResult'];
    }
  }

  function SearchList(){
    if(!empty($this->DefaultSearchColumn)){
      //Draw search form
      $URI = GetGET();
      
      $Form= "<form action=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?$URI method='GET' class='Search'>";
      unset($_GET['listcrit']);
      unset($_GET['listcolumn']);
      $Form .= "<input name=listcrit type=search list=search>";

      // Making datalist for SearchBox
      $Form .= "<datalist id=search>";
      $Tape=$this->GoodBoi->GoFetch();
      
      $Ketan=explode(", ",$this->DefaultSearchColumn);
      foreach($Tape as $y=>$x){
        $Form .= "<option value=select__". $x[$this->TID] .">";
        foreach($Ketan as $b=>$a){
          $Arm .= $x[$a] ." ";
        }
        $Form .= $Arm;
        unset($Arm);
        $Form .= "</option>";
      }
      $Form .= "</datalist>";

      $Form .= "<select name=listcolumn >";
      $Form .= "<option value=ALL selected>". $GLOBALS['lanAll'] ."</option>";

      // Making the search column list
      $rpl= csv2csv($this->DefaultSearchColumn);
      $Label=$this->ColumnLabelObj->GoFetch("WHERE form_id='". $this->FormID ."' AND field_id IN (". $rpl .")","field_label, field_id");
      foreach ($Label as $x){
        $Form .= "<option value=". $x['field_id'] .">". lan2var($x['field_label']) . "</option>";
      }

      $Form .= "</select>";
      foreach ($_GET as $y=>$x){
        $Form .= "<input type=hidden name=$y value=$x>";
      }
      $Form .= "<input type=submit value=". $GLOBALS['lanSearch'] ."></input>";
      $Form .= "</form>";
      return $Form;
     }
    }

    function Gardevoir(){
      Gardevoir($this->SearchList());
      Gardevoir($this->Gardevoir);
    }
}


/*
=======================================================================================
 █████╗ ██████╗ ██████╗     ████████╗ ██████╗     ██╗     ██╗███████╗████████╗
██╔══██╗██╔══██╗██╔══██╗    ╚══██╔══╝██╔═══██╗    ██║     ██║██╔════╝╚══██╔══╝
███████║██║  ██║██║  ██║       ██║   ██║   ██║    ██║     ██║███████╗   ██║   
██╔══██║██║  ██║██║  ██║       ██║   ██║   ██║    ██║     ██║╚════██║   ██║   
██║  ██║██████╔╝██████╔╝       ██║   ╚██████╔╝    ███████╗██║███████║   ██║   
╚═╝  ╚═╝╚═════╝ ╚═════╝        ╚═╝    ╚═════╝     ╚══════╝╚═╝╚══════╝   ╚═╝   
=======================================================================================
For the the 'datalist' field, check Add to list_list if entry is new                                                                            
*/
class AddList{
  protected $Data; // Array containing POST of form
  protected $Layout; // Object of GoodBoi layout
  protected $List; //Object of GoodBoi List

  function __Construct($Data,$Layout=null,$List=null){
    if (empty($Layout)) { $Layout= new GoodBoi('layout'); }
    if (empty($List)) { $List= new GoodBoi('list_list'); }
    $this->Layout = $Layout;
    $this->List = $List;
    $this->Data=$Data;
    mark($this->Data, "DATA ");
    $this->InsertList();
  }

  function CekIfNew($ListID,$Cluster){
    $List=$this->List->GoCount("WHERE cluster='". $Cluster ."' AND list_value='". $ListID ."'");
    mark($List,"LIST ");
    if($List == 0 ){ mark("NEW LIST"); return TRUE; } else { mark("OLD LIST"); return FALSE; }
  }

  function InsertList(){
    $Layout=$this->Layout->GoFetch("WHERE field_type='datalist' && field_list_table IS NULL", "field_id, field_list");
    $Lay=array(); //Lay is array (field_id => field_list) of those field type datalist
    //Make $Lay
    foreach($Layout as $x){
      $Lay += array($x['field_id']=>$x['field_list']);
    }
    DeFlea($Lay, "Lay");

    foreach($this->Data as $y=>$x){
      if(array_key_exists($y,$Lay)){
        if($this->CekIfNew($x,$Lay[$y]) === TRUE && !empty($x)){
          $Kue=$this->MakeQuery($x,$Lay[$y]);
          DeFlea($Kue, "Kue ");
          $this->List->GoBark($Kue);
          
        }
      }
    }
  }

  function MakeQuery($Data,$Cluster){
    $Ord=$this->List->GoCount("WHERE cluster='". $Cluster ."'");
    $Que=array('cluster'=>$Cluster,'list_name'=>$Data,'list_value'=>$Data,'active'=>1,'list_order'=>$Ord+1,'creator'=>$_SESSION['Person']);
    $BARK= new Snorlax ('id','com_list_list',$Que,'New',$this->List);
    return $Que;

  }
}

?>

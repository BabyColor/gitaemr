

<?php

//Debug



function DeFlea($Funk,$Egg="Nothing",$Flea="Nothing"){ 
  echo "<br><table class='DeFlea'><th> Function [$Funk] initiated </th><tr><td> Argument is ";
  print_r($Egg);
  echo "<tr><td> It's returned ";
  print_r($Flea);
  echo " </table><bR>";
}

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
  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

  return new mysqli($Server['Host'], $Server['Person'], $Server['Lock'], $DBase);
}

//-------------------User-----------------------
function Login($User){
  $userlist=new GoodBoi("staff_list");
  $x=$userlist->GoFetch("WHERE usrid='$User'");
  $_SESSION['Person']=$User;
  $_SESSION['UserN']=$x[0]['UserName'];
  $_SESSION['Name']= $x[0]['FName'] ."/". $x[0]['MName'] ."/". $x[0]['LName'];
  $_SESSION['ULevel']=$x[0]['UserLevel'];
  $_SESSION['UGroup']=$x[0]['UserGroup'];
  LogUser($User);
}

function Logout(){
  unset($_SESSION['Person']);
  unset($_SESSION['UserN']);
  unset($_SESSION['Name']);
  unset($_SESSION['ULevel']);
  unset($_SESSION['UGroup']);
}

function LogUser($User,$Userclass=null){ // Update the Last IP and Last activity of user (User ID, Class that linked to staff_list db)
	$Who=WhoAreYou();
	$Who=serialize($Who);
  $Updatecol=array("LastActiveTime"=>Date2SQL(), "LastActiveIP"=>$_SERVER['REMOTE_ADDR'], "LastActiveInfo"=>$Who);
  if(empty($Userclass)){ $Userclass= new GoodBoi("staff_list"); } // If $Userclass empty, make a new class to link to staff_list db
  $Userclass->GoBurry($Updatecol,"WHERE usrid=$User");
}


// Function to check user permission based on usergroup, level, and specifi user. Only one statement needed to be true.
// (group(array of allowed usergroup), level(integer of minimum user level allowed), user(array of specific user allowed))
function Bouncer($group,$level,$person){
  if(in_array($_SERVER['UGroup'],$group)){ return TRUE; } // Check for User Group
  if($_SERVER['ULevel']>=$level){ return TRUE; } // Check for User Level
  if(in_array($_SERVER['Person'],$person)){ return TRUE; } // Check for Specific User
}

//----------------- CONVERTER--------------------------

function array2csv($arr,$bra=null,$IgnoreBlank=null,$IgnoreExc=null,$sep=" , "){ // Ignore Blank=Ignore value with Blank Key, IgnoreExc=Ignore any value with key started with "Exc-"
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



function array2arrow($arr,$arrow=" ==> ",$sep=" || ",$valpre="'",$valpost="'",$keypre="",$keypost=""){ // Turn array into key => value (Array,Arrow,Seperator)
  mark($arr,"A2A ") ;
  foreach($arr as $a => $b){
    if(!empty($counter)){  // Assign seperator if not first value
      $aa = $aa . $sep;
    }
    $aa = $aa. $keypre . $a . $keypost . $arrow . $valpre . $b . $valpost;
    $counter++;
  }
  if(!empty($_SESSION['DeFlea'])){ Mark($aa,"[". __FUNCTION__ ."]","<br>". $bun->error); } 
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

function Array2List($ListArray,$Pre=null,$InPre=null,$InPos=null,$Post=null){ //Turn array into <ul>, (Array,Pre Text, In list pretext, IN list postext, Post text)
  $Pre= $Pre . "<br><ul>";
	foreach($ListArray as $i){
     $Pre= $Pre . "<li>$InPre $i $InPos</li>";
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
//-----------------------------Salvage Data---------------------


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
function DrawOption($cluster,$ListDB){
  $Selection=$ListDB-> GoFetch("WHERE cluster = '". $cluster ."' AND active <> 0 ORDER BY list_order"); //Chose list in certain cluster which active (active not null)
    foreach($Selection as $L){
      if(!empty($L['default'])){ $Default="selected=selected"; }
      $Options = $Options ."<option value=". $L['list_value'] ." $Default>". lan2var($L['list_name']) ."</option>";
    }
  return $Options;
}

//---------------------Filtering & Validation-----------------------------------------


function ValidateField($Field,$Rule=null){ // Validating input data
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

function AddDatalist($input,$cluster,$LastOrder=0,$position="Last"){ //Creating new list in list_list if datalist input is new data 
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

/*MySQL query
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
    $result = $ewe->query($query);

     //Debug
      if(!empty($_SESSION['DeFlea'])){ Mark($query,__CLASS__ ." ==> ". __FUNCTION__);}
   //Debug
   $row = $result->fetch_all(MYSQLI_ASSOC); 
 
   return $row;
  }

  public function GoBark ($data){ // Method to INSERT data, $data is an array (Column as Key, and Values as Value))
    $bun = SniffButt();
    $dataesc= Blissey($data);
    $filling=array2csv($dataesc,"'",null,1);
    $sausage= "INSERT INTO ". $this->table ." (". $filling['Key'] .") VALUES (". $filling['Val'] ."); ";
    $bun->query($sausage);
    //Debug
   if(!empty($_SESSION['DeFlea'])){ Mark($sausage,"[". __CLASS__ ." ". __FUNCTION__ ."]","<br>". $bun->error); } 
   //Debug
    
  }
  public function GoCount($a){
    $RowCount=$this->GoFetch($a,"COUNT(*) as Total");
    //Debug
    
   if(!empty($_SESSION['DeFlea'])){ Mark("Row Count is : [". $RowCount[0]['Total'] ."] for $a"); } 
   //Debug
      
   return $RowCount[0]['Total'];
  }

  public function GoBurry($data,$selection){ //Method to Update SQL data ("WHERE bla bla bla", array(column=>new value))
    $bun = SniffButt();
    $dataesc= Blissey($data);
    $filling=array2arrow($dataesc,"="," , ");
    $sausage= "UPDATE ". $this->table ." SET $filling $selection";
   mark($sausage,"[","]");
    //Debug
   if(!empty($_SESSION['DeFlea'])){ Mark($sausage,"[". __CLASS__ ." ". __FUNCTION__ ."]","<br>". $bun->error); } 
   $bun->query($sausage);
   //Debug

  
   
   

  }

}
/////////////////////////////////////////////////////////////////





//--------------------Display-----------------------

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
  echo "<br><p class=Mark>$pre ";
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

class Smeargle{
  protected $ViewMode; //----Which viewing mode ? "view", "new" or "edit"
  protected $Layout; //----The Form Id
  protected $admin; //-----admin access?
  protected $Style; //---Style CSS
  protected $MySQL_Object; //---Class that link to layout table
  protected $MySQL_Staff; //---Class that link to staff_list table

  function __Construct($form,$view,$mysqlclass=null,$staffdbclass=null,$adminlevel=69,$style="layout_form"){ // (Form id, Viewing Mode (Edit, View), Class that handle linking to MySQL DB if available (if leave blank, this calss will make it's own conenction), same as previous, but this link to staff_list table, Minimal User Level to access admin-only-content, css style)
    if(!empty($_SESSION['DeFlea'])){ mark("Drawed", __CLASS__ ." ----> ". __FUNCTION__ ."   "); }
    $this->ViewMode=$view;
    $this->Layout=$form;
    $this->adminlevel=$adminlevel;
    $this->Style=$style;
    //Make new class to link to layout table
    if(empty($mysqlclass)){ 
      $mysqlclass= new GoodBoi("layout"); 
      if(!empty($_SESSION['DeFlea'])){ mark("Linking to GoodBoi Layout"); } }
    //Make new class to link to staff_list table
    if(empty($staffdbclass)){ 
      $staffdbclass= new GoodBoi("staff_list"); 
      if(!empty($_SESSION['DeFlea'])){ mark("Linking to GoodBoi Layout"); } }
    $this->MySQL_Object=$mysqlclass;
    $this->MySQL_Staff=$staffdbclass;
    if($_SESSION['ULevel']>=$adminlevel){ $this->admin=" || field_visible_admin !=0"; } //If admin level is high enough, grant admin 
  }

  // Function for drawing: Work by putting the HTML code to a variable $Draw, and append ne HTML code to the variable each time it make new code, an then returned it as return, so it can be echoed with the caller to really draw the form (Use $Resultstring=str_replace('$lan',"$lan",$Resultstring) to convert all the 'turned to string' $lan... to proper variable $lan...)
  function DrawForm($Button=null,$method="POST"){
    //Button is array (Submit Edit,Cancel,Edit,Submit New)
   
    //Debug
    if(!empty($_SESSION['DeFlea'])){ mark("Drawed", __CLASS__ ." ----> ". __FUNCTION__ ."   "); }
   
    foreach($_GET as $x=>$y){
      $URI=$URI. "$x=$y&";
    }

    //Draw form pening ( <form> and <table>)
    $Draw= "<form action=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?$URI method=$method class=". $this->Style ."><table class=". $this->Style ."><tr>"; 
    
    //---Draw form
   $Draw= $Draw . $this->Grouping(); //  Go to function Grouping to draw each group
   
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
      $Draw= $Draw . "<tr><td colspan=2><input type=Submit value=$Button[0]></input> <input type=reset value=$Button[1]></input>";
      break;
    case "edit":
      $Draw= $Draw . "<tr><td colspan=2><input type=Submit value=$Button[2]></input> <input type=reset value=$Button[1]></input>"; 
      break;
    case "view":
     $Draw= $Draw . "<tr><td colspan=2>$lanEdit";
      break;
   }

   //Draw closur </table> and </form>"
   $Draw= $Draw . "</table></form>";
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
      case "new":
        $Draw=$Draw . $this->NewFielding($Field,$Group);
        break;
      case "view":
        $Draw=$Draw . $this->ViewFielding($Field,$Group);
        break;
      case "edit":
        $Draw=$Draw . $this->NewFielding($Field,$Group);
        break;
    }
    return $Draw;
  }


  // Draw field if viewing mode is Edit or New
  function NewFielding($Field,$Group){
    
    //Make connection to list_list list table for use latter
    $List=new GoodBoi("list_list");

    foreach($Field as $F){ // Script for each found filed
      
      // Assign attributes
      $minlength= empty($F['field_minlength']) ? "" : "minlength=". $F['field_minlength'] ;
			$placeholder= empty($F['field_placeholder'] ) ? "" : "placeholder=". $F['field_placeholder'] ;
      $validator= empty($F['field_validation'] ) ? "" : "pattern=". $F['field_validation'] ;
      
      // Determining input type, and how to draw them
      switch($F['field_type']){
        case "select":
          $Input="select";
          $Close="select";
          $InputContent = DrawOption($F['field_id'],$List);
          break;
        case "datalist":
          $Input="input list=". $F['field_id'];
          $Close="input";
          $InputContent = "<datalist id=". $F['field_id'] .">". DrawOption($F['field_id'],$List) ."</datalist>";
          break;
        case "password":
          $Input="input type=password";
          $Close="input";
          if($this->ViewMode=="edit"){
            $Pre= "<tr><td>". $GLOBALS['lanOldPass']. "<td><input type=password name=Exc-". $F['field_id'] . "Old></input>" ;
          }
          $Additional="<tr><td>". $GLOBALS['lanConfirmPass'] ."<td><input type=password name=Exc-".  $F['field_id'] ."Conf></input>";
          break;
        default:
          $Input="input type=". $F['field_type'];
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
                          ". $F['field_label'] ."
                        </td>
                        <td>
                          <$Input name=". $F['field_id'] .">
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
    $Staff=$this->MySQL_Staff-> GoFetch("WHERE usrid = ". $_SESSION['Person'] );
    foreach($Field as $F){ // Script for each found filed
      
      if(empty($Draw)){ $Draw= "<tr><th colspan=2>$Group</th></tr>"; } //Group Header (Drawed only when at least one element showed, and only once in each group)
      $Draw= $Draw . "<tr>
                        <td>
                          ". $F['field_label'] ."
                        </td>
                        <td>
                          ". $Staff[0][$F['field_id']] ."
                        </td>
                      </tr>"; // Input
    }
    return $Draw;
  }
}

//---------------------Logging Class------------

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
    $Kayuing=array('SDesc'=>$a , 'Culpirt'=>$l['IP'], 'CBrowser'=>$l['Browser'], 'CPort'=>$l['Port'], 'Victim'=>$l['sIP'], 'Vport'=>$l['sPort'], 'VURI'=>$l['URI'], 'Command'=>$b, 'Error'=>$d, 'User'=>$_SESSION['Person'], 'UserN'=>$_SESSION['UserN'], 'Name'=>$_SESSION['Name'], 'ULevel'=>$_SESSION['ULevel'], 'UGroup'=>$_SESSION['UGroup']); 
    $this->GoBark($Kayuing);
    //Debug
   if(!empty($_SESSION['DeFlea'])){ Mark($Kayuing,"LOG || "); } 
   //Debug
  }
}


//////////////////////////////////////// TESTING FUNCION //////////////////////////////////////////




?>

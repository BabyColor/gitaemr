

<?php
/*
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
/*
==========================================================================================================





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
function DrawOption($cluster,$ListDB){
  $Selection=$ListDB-> GoFetch("WHERE cluster = '". $cluster ."' AND active <> 0 ORDER BY list_order"); //Chose list in certain cluster which active (active not null)
    foreach($Selection as $L){
      if(!empty($L['default'])){ $Default="selected=selected"; }
      $Options = $Options ."<option value=". $L['list_value'] ." $Default>". lan2var($L['list_name']) ."</option>";
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


function DeFlea($Funk,$Egg="Nothing",$Flea="Nothing"){ 
  echo "<br><table class='DeFlea'><th> Function [$Funk] initiated </th><tr><td> Argument is ";
  print_r($Egg);
  echo "<tr><td> It's returned ";
  print_r($Flea);
  echo " </table><bR>";
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



/*
==========================================================================================================







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


/*
==========================================================================================================











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
    $Kayuing=array('SDesc'=>$a , 'Culpirt'=>$l['IP'], 'CBrowser'=>$l['Browser'], 'CPort'=>$l['Port'], 'Victim'=>$l['sIP'], 'Vport'=>$l['sPort'], 'VURI'=>$l['URI'], 'Command'=>$b, 'Error'=>$d, 'User'=>$_SESSION['Person'], 'UserN'=>$_SESSION['UserN'], 'Name'=>$_SESSION['Name'], 'ULevel'=>$_SESSION['ULevel'], 'UGroup'=>$_SESSION['UGroup']); 
    $this->GoBark($Kayuing);
    //Debug
   if(!empty($_SESSION['DeFlea'])){ Mark($Kayuing,"LOG || "); } 
   //Debug
  }
}

/*
==========================================================================================================











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
  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

  return new mysqli($Server['Host'], $Server['Person'], $Server['Lock'], $DBase);
}

/*
==========================================================================================================











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
















































/*
==============================================================================================================
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
==============================================================================================================
_________ .____       _____    _________ _________
\_   ___ \|    |     /  _  \  /   _____//   _____/
/    \  \/|    |    /  /_\  \ \_____  \ \_____  \ 
\     \___|    |___/    |    \/        \/        \
 \______  /_______ \____|__  /_______  /_______  /
        \/        \/       \/        \/        \/ 
==============================================================================================================
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
==============================================================================================================
*/

/*

==============================================================================================================  ________                  ._____________       .__ 
 /  _____/  ____   ____   __| _/\______   \ ____ |__|
/   \  ___ /  _ \ /  _ \ / __ |  |    |  _//  _ \|  |
\    \_\  (  <_> |  <_> ) /_/ |  |    |   (  <_> )  |
 \______  /\____/ \____/\____ |  |______  /\____/|__|
        \/                   \/         \/           
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



















/*

==============================================================================================================   _________                                  .__          
 /   _____/ _____   ____ _____ _______  ____ |  |   ____  
 \_____  \ /     \_/ __ \\__  \\_  __ \/ ___\|  | _/ __ \ 
 /        \  Y Y  \  ___/ / __ \|  | \/ /_/  >  |_\  ___/ 
/_______  /__|_|  /\___  >____  /__|  \___  /|____/\___  >
        \/      \/     \/     \/     /_____/           \/ 
==============================================================================================================



Displaying Layout
////////////////////////////////////////////////////////////////////*/



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
          $InputContent = DrawOption($F['field_list'],$List);
          break;
        case "datalist":
          $Input="input list=". $F['field_id'];
          $Close="input";
          $InputContent = "<datalist id=". $F['field_id'] .">". DrawOption($F['field_list'],$List) ."</datalist>";
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

/*
	===============================================================================================
___________.__       .__       .___ ____   ____      .__  .__    .___       __                 
\_   _____/|__| ____ |  |    __| _/ \   \ /   /____  |  | |__| __| _/____ _/  |_  ____   ____  
 |    __)  |  |/ __ \|  |   / __ |   \   Y   /\__  \ |  | |  |/ __ |\__  \\   __\/  _ \ /    \ 
 |     \   |  \  ___/|  |__/ /_/ |    \     /  / __ \|  |_|  / /_/ | / __ \|  | (  <_> )   |  \
 \___  /   |__|\___  >____/\____ |     \___/  (____  /____/__\____ |(____  /__|  \____/|___|  /
     \/            \/           \/                 \/             \/     \/                 \/ 
	===============================================================================================
	*/ 
  class FieldValidation{
    public $SignUpError; //Array to store sign up error
    protected $MyClass; // A GoodBoi class for layout
    protected $MyClasStaff; // A GoodBoi class for staff
    protected $FormId; // Form Id that validate (Form id where the field POST-ed) came from
    public $Error1; // Array of field(s) whom didn't pass Error1 check (Empty value on required field)
    public $Error2; // Array of field(s) whom didn't pass Error2 check (Duplicate on Unique field)
    public $Error3; // Array of field(s) whom didn't pass Error3 check (Invalid character)
  
    function __Construct($FormId,$MyClass=null,$MyStaff=null,$E0S=null,$EOV=null,$E1=1,$E2=1,$E3=1){
      // (Form ID, GoodBoi class, Run Check error1, error2, error3)
    $this->SignUpError=array(); 
    $this->FormId=$FormId;
    $this->MyClass=$MyClass;
    $this->MyClasStaff=$MyStaff;
    if (!empty($E0S)){ $this->RepeatString($E0S,$E0V); }
    if (!empty($E1)){ $this->Required(); }
    if (!empty($E2)){ $this->Unique(); }
    if (!empty($E3)){ $this->ValidateField(); }
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
  }
  
?>

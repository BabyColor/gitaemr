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
  if (is_array($A)){  return json_encode($A); } else { return $A; }
}
function ArrayUnserialize($A){
  $B=json_decode($A);
  if ($B == JSON_ERROR_NONE){
    return $B;
  } else {
    return $A;
  }
}

function UnJson($A){
  $B=json_decode($A,true);
  // Is passed arguments JSON?
  if ($B){
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

function NameSep($name){
  $name=explode(" ",$name);
  $LName=array_pop($name);
  $name=implode(" ",$name);
  return array('FName'=>$name,'LName'=>$LName);
}

//For proccessin Diagnosis
//Automatically detect wether the job is to write or read
function DxEater($Alpha,$Method='Dx'){
  //$Alpha = [ARRAY] data of diagnosis, to WRITE (dx=>x,type=>x,grade=>x,grade=>x... etc) will return a JSON ("dxid":"id","note":"note") from the diagnosis table that matched the input and the note inputed, or to READ (dxid=>x,note=>x) will return an array of mysql fetch from diagnosis table with id of dxid (also with the note)
  //$Method = The method, 'Dx' : Diagnosys || 'Sym' = Symptomp
  
  switch ($Method){
    case 'Dx':
      $EatTable = "com_gita_visit_diagnosis";
      $idPsCol = "dx";
    break;
    case 'Sym':
      $EatTable = "com_gita_visit_symptomp";
      $idPsCol = "symptomp";
    break;
    case 'Med':
      $EatTable = "com_gita_medicine";
      $idPsCol = "name";
    break;
  }
  
  
  $DB = new GoodBoi($EatTable);
  $Beta = UnJson($Alpha);
  $jonson=array();

  foreach($Beta as $c=>$DxD){
    
    $DxD = UnJson($DxD);
    foreach($DxD as $y=>$o){
      $DxD[$y] = trim($o);
    }
      
      /*
        'dxid' structure => name_#(random)
      */

      // WRITER
      /*
        Flow:
          1. Check if diagnosis already exist =>No? Make it
          2. Check if type, grade, stage, and causa for that diagnosis exist? =>No? Make it
          4. Get the 'dxid' as $dxid
          5. Return JSON of array(['dxid'] and ['note'])
      */
      if(array_key_exists($idPsCol,$DxD)){ //Check if 'dx' key exist, therefore, the job is to WRITE
        $Que = $DB->GoFetch(" WHERE $idPsCol ='". $DxD[$idPsCol] ."'");

        switch ($Method){
          case 'Dx':
            $NewDX=array('dx'=>$DxD['dx'], 'type'=>$DxD['type'],'stage'=>$DxD['stage'],'grade'=>$DxD['grade'],'causa'=>$DxD['causa'],'location'=>$DxD['location']); 
          break;
          case 'Sym':
            $NewDX=array('symptomp'=>$DxD['symptomp'], 'location'=>$DxD['location'],'reffered_pain'=>$DxD['reffered_pain'],'duration'=>$DxD['duration'],'frequency'=>$DxD['frequency'],'quality'=>$DxD['quality'],'worsened_by'=>$DxD['worsened_by'],'relieved_by'=>$DxD['relieved_by']); 
          break;
        }

        //Aray of data to be inserted as new dx if new dx needed to inserted
        array_map('Empty2Null',$DxD); //Return empty value as Null
        

        if($Que){ // #1 Check if diagnosis already exist
          $dxid=$Que[0]['id'];
          $dxidx=explode('_',$dxid);
          $dxidx=$dxidx[0];

          switch ($Method){
            case 'Dx':
              $FetchQ="WHERE dx='". $DxD['dx'] ."' AND type='". $DxD['type'] ."' AND stage='". $DxD['stage'] ."' AND grade='". $DxD['grade'] ."' AND causa='". $DxD['causa'] ."' AND location='". $DxD['location'] ."'";
            break;
            case 'Sym':
              $FetchQ="WHERE symptomp='". $DxD['symptomp'] ."' AND location='". $DxD['location'] ."' AND reffered_pain='". $DxD['reffered_pain'] ."' AND duration='". $DxD['duration'] ."' AND frequency='". $DxD['frequency'] ."' AND quality='". $DxD['quality'] ."' AND worsened_by='". $DxD['worsened_by'] ."' AND relieved_by='". $DxD['relieved_by'] ."' "; 
            break;
          }
          
          $Que = $DB->GoFetch($FetchQ); 
          $NewDX +=array('walkthrough'=>$Que[0]['walkthrough']);


            if($Que){// #2 Check if diagnosis with indentical attributs already exist
              $dxid=$Que[0]['id']; //#4
            } 

          else { //Write dx with new attributes
            do { // Generate new 'dxid' with dxname+_random number, if already exist, repeat
              $dxid=$dxidx ."_". rand(1,1000);
            } while ($DB->GoFetch("WHERE id = '$dxid'"));
            $NewDX +=array('id'=>$dxid);
            new Snorlax('id',$EatTable,$NewDX,null,'New');
          }
        } 

        else { //Wirte new dx
          $dxid_pre=strtolower(str_replace(" ","-",$DxD['dx']));
        while ($DB->GoFetch("WHERE dx LIKE '$dxid_pre%'")){// Generate new 'dxid' with dxname+_random number, if already exist same dx prefix, use dxname+randomnumber+_randomnumber
            $dxid_pre=$dxid_pre . rand(1,100);
          }
          $dxid=$dxid_pre . "_1";
          $NewDX +=array('id'=>$dxid);
          new Snorlax('id',$EatTable,$NewDX,null,'New');
        }
        $Inserted = array("dxid"=>$dxid, "note"=>$DxD['note']);
        if($DxD['susp']) { $Inserted += array("suspect"=>TRUE); }
        array_push($jonson,json_encode($Inserted)); // #5 Return WRITER
      } 
      
      // READER
      elseif(array_key_exists('dxid',$DxD)){ //Check if 'dxid' key exist, therefore, the job is to READ
        $Que = $DB->GoFetch("WHERE id='". $DxD['dxid'] ."'");
        $Qlue[$c] = $Que[0] + array('Thisnote'=>$DxD['note'],'Susp'=>$DxD['suspect']);
      }
  }
  if($jonson) { return json_encode($jonson); }
  if($Qlue) { return $Qlue; }
  
}


//For proccessin Symptomp
//Automatically detect wether the job is to write or read
function SympEater($Alpha){
  //$DxD = [ARRAY] data of diagnosis, to WRITE (dx=>x,type=>x,grade=>x,grade=>x... etc) will return a JSON ("dxid":"id","note":"note") from the diagnosis table that matched the input and the note inputed, or to READ (dxid=>x,note=>x) will return an array of mysql fetch from diagnosis table with id of dxid (also with the note)
  $DB = new GoodBoi("com_gita_visit_symptomp");
  $Beta = UnJson($Alpha);
  $jonson=array();
  foreach($Beta as $c=>$DxD){
    
    $DxD = UnJson($DxD);
    
      
      /*
        'dxid' structure => name_#(random)
      */

      // WRITER
      /*
        Flow:
          1. Check if diagnosis already exist =>No? Make it
          2. Check if type, grade, stage, and causa for that diagnosis exist? =>No? Make it
          4. Get the 'dxid' as $dxid
          5. Return JSON of array(['dxid'] and ['note'])
      */
      if(array_key_exists('dx',$DxD)){ //Check if 'dx' key exist, therefore, the job is to WRITE
        $Que = $DB->GoFetch("WHERE dx='". $DxD['dx'] ."'"); 
        $NewDX=array('dx'=>$DxD['dx'], 'type'=>$DxD['type'],'stage'=>$DxD['stage'],'grade'=>$DxD['grade'],'causa'=>$DxD['causa'],'location'=>$DxD['location']); //Aray of data to be inserted as new dx if new dx needed to inserted
        array_map('Empty2Null',$DxD); //Return empty value as Null

        if($Que){ // #1 Check if diagnosis already exist
          $dxid=$Que[0]['id'];
          $dxidx=explode('_',$dxid);
          $dxidx=$dxidx[0];
          $Que = $DB->GoFetch("WHERE dx='". $DxD['dx'] ."' AND type='". $DxD['type'] ."' AND stage='". $DxD['stage'] ."' AND grade='". $DxD['grade'] ."' AND causa='". $DxD['causa'] ."' AND location='". $DxD['location'] ."'"); 
          $NewDX +=array('walkthrough'=>$Que[0]['walkthrough']);


            if($Que){// #2 Check if diagnosis with indentical attributs already exist
              $dxid=$Que[0]['id']; //#4
            } 

          else { //Write dx with new attributes
            do { // Generate new 'dxid' with dxname+_random number, if already exist, repeat
              $dxid=$dxidx ."_". rand(1,1000);
            } while ($DB->GoFetch("WHERE id = '$dxid'"));
            $NewDX +=array('id'=>$dxid);
            new Snorlax('id','com_gita_visit_diagnosis',$NewDX,null,'New');
          }
        } 

        else { //Wirte new dx
          $dxid_pre=strtolower(str_replace(" ","-",$DxD['dx']));
        while ($DB->GoFetch("WHERE dx LIKE '$dxid_pre%'")){// Generate new 'dxid' with dxname+_random number, if already exist same dx prefix, use dxname+randomnumber+_randomnumber
            $dxid_pre=$dxid_pre . rand(1,100);
          }
          $dxid=$dxid_pre . "_1";
          $NewDX +=array('id'=>$dxid);
          new Snorlax('id','com_gita_visit_diagnosis',$NewDX,null,'New');
        }
        $Inserted = array("dxid"=>$dxid, "note"=>$DxD['note']);
        if($DxD['susp']) { $Inserted += array("suspect"=>TRUE); }
        array_push($jonson,json_encode($Inserted)); // #5 Return WRITER
      } 
      
      // READER
      elseif(array_key_exists('dxid',$DxD)){ //Check if 'dxid' key exist, therefore, the job is to READ
        $Que = $DB->GoFetch("WHERE id='". $DxD['dxid'] ."'");
        $Qlue[$c] = $Que[0] + array('Thisnote'=>$DxD['note'],'Susp'=>$DxD['suspect']);
      }
  }
  if($jonson) { return json_encode($jonson); }
  if($Qlue) { return $Qlue; }
  
}

// Turn empty string into null (Good for MySQL)
function Empty2Null($Data){
  if(!$Data || $Data=''){
    return 'null';
  }
}

//JSON to list. Turn each JSON element as <li> with random number id
//$JSON = JSON ==> Structure ==> array(array(key=>val),array(key=>val),array(key=>val),...)
//$Disp = ARRAY Displayed Key and their connector word : array(array('PRE','key name','POST')). Ex: will be displayed as "<li>PRE key POST</li> (without spacing)"
/////////ARGUMENTS
//////idPrefix = Prefix of list ID (List id will be "prefix $listIdKey" for each list)
//////listIdKey = Wich Key will be used to determined the each list's ID, if empty, will generate random number instead
//////liClass = Class of each list
//////liClassSusp = Class of <li> for those with 'susp'
//////preClass = Class of connector word
//////postClass = Class of connector word
//////removeButton = BOOLEAN, add remove button on each <li> end
//////hiddenVal = BOOLEAN, Make hidden div with class 'jonson' on each which contain JSON of the <li>
//////walkthrough = BOOLEAN, display walktrought hidden box

//CALLBACK: the list(s) will be returned as array

function JSON2List($JSON,$Display,$Arguments=array('idPrefix'=>'list_','class'=>'w3-list')){
  $UsedID = array();
  $JSON = PhoenixDown($JSON);
  foreach($JSON as $y => $x){
    //Make list ID
    $liID = $Arguments['listIdKey']? $x[$Arguments['listIdKey']] : rand(1,99999) ;
    while(in_array($liID,$UsedID)){
      $liID = rand(1,99999) ;
    }
    
    array_push($UsedID,$liID);
    $liID = $Arguments['idPrefix'] . $liID;
    mark($x,"JSON");

    $Class = $x['Susp']? $Arguments['liClassSusp'] : $Arguments['liClass'];

    $list[$y] = "<li id='" . $liID ."' class='$Class' onclick=BukaTutup('walk_$liID')>";
    foreach ($Display as $a){
      if(!$x[$a[1]]) { continue; }
      $list[$y] .= "<span class='". $Arguments['preClass'] ."'>". $a[0] . "</span>" . $x[$a[1]] . "<span class='". $Arguments['postClass'] ."'>" . $a[2] . "</span>";
    }

    if($Arguments['removeButton']){ // Add Remove button
      $list[$y] .= "<span class='w3-right-align w3-text-red rem'> [X]<script>$('.rem').on('click', function() {
        $(this).parent().addClass('w3-red');
        $(this).parent().remove();
      });</script></span>";
    }

    if($Arguments['hiddenVal']) { // Hidden jonson div
      $list[$y] .= '<span class="jonson" hidden="">';
      $list[$y] .= ArraySerialize($x);
      $list[$y] .= '</span>';
    }

    if($Arguments['walkthrough']) { // walkthrough
      $list[$y] .= "<div id='walk_$liID' class='w3-container w3-hide w3-white'>". walkthrough($x['walkthrough']) ."</div>";
    }

    $list[$y] .= "</li>";
    
  }
  mark($list,"LIST ");
  return $list;
}

//Put into data, if new medicine, and update attributes of already existed medicine
//$Data = ARRAY of medicines
//$New = ARRAY of New Medicines
function NewMedicine($Data,$New){
  $Data = PhoenixDown($Data);
  $Attrib = array('form','adm','rule','ext');
  $MyMed = new GoodBoi('com_gita_medicine');
  //Check if there are new drugs, and add
  foreach($New as $x){
    //Check if already identical drug?
    $CheckMed = $MyMed -> GoFetch(array('name'=>$x['name'],'comp'=>ArraySerialize($x['comp']),'type'=>$x['type'], 'form_n'=>$x['form_n'],'form'=>ArraySerialize($x['form']),'adm'=>ArraySerialize($x['adm']),'rule'=>ArraySerialize($x['rule']),'ext'=>ArraySerialize($x['ext'])));
    if($CheckMed){ 
      $NewID = $CheckMed[0]['id'];
     } else {
      $OldID = $x['id'];
      unset($x['id']);
      $x = array_map('ArraySerialize',$x);
      $Ins=new Snorlax('id','com_gita_medicine',$x,null,'New',null,FALSE);
      $NewID = $Ins -> IVs();
    }
    foreach($Data as $b => $a){
      if($a['id']==$x['id']){
        $Data[$b]['id'] = $NewID; 
        break;
      }
    }
  }
  //Check if attributes is new, and add
  foreach($Data as $x){
    if(strrpos( $x['id'],'NEW_')!==FALSE){ continue; }
    $MedData = $MyMed -> GoFetch("WHERE id = '". $x['id'] ."'");
    $MedData = PhoenixDown($MedData);
    foreach($Attrib as $a){
      if(!$x[$a]) { continue; } // If inputed attributes is empty, skip current loop
      if(!in_array($x[$a],$MedData[0][$a])){ // If inputed attributes is new (Not already in list, add them)
        mark($MedData[0][$a],$x[$a]  ." IS NOT IN ARRAY YOO");
        array_push($MedData[0][$a],$x[$a]);
        $MyMed -> GoBurry(array($a=>json_encode($MedData[0][$a])),array('id'=>$x['id']));
      }
    }
  }
  return $Data;
}
/*
************************************************************************************************
             /<                                             
            /<                                              
  |\_______{o}----------------------------------------------------------_
 [\\\\\\\\\\\{*}:::<===================  CALCULATION =============-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/

//------------------Get Age from MySQL Date (YY-mm-dd)
function getAge($BD){
  $birthday = new DateTime($BD);
  $diff = $birthday->diff(new DateTime());
  return $diff->format('%m') + 12 * $diff->format('%y');
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
function DrawOption($array,$Arguments){
  /*
  $array=ARRAY of fetched layout row
  ==========$ARGUMENTS OPTION=========
  default=Wich default option to chose
  orderby=Order by ASC or DESC

  
  Custom list:
    >>Table = DB Table
    >>Option = Option Column
    >>Value = Value Column
    >>Order = Order By Column
    >>WhereQuery = Where Query
    >>Select = MySQL Select Query (Default is all (*))
  */
  $default= $Arguments['default']? $Arguments['default'] : null;
  $orderby= $Arguments['orderby']? $Arguments['orderby'] : "ASC";

  $CustomListDB= UnJson($array['field_list_table']);
  $ListDB= empty($CustomListDB)? new GoodBoi("list_list") : new GoodBoi($CustomListDB['Table']);
  $Opt= $CustomListDB? $CustomListDB['Option']: "list_name";
  $Val= $CustomListDB? $CustomListDB['Value']: "list_value";
  if($CustomListDB){ // Make list using custom table
    $CustomListDB['WhereQuery'] = $CustomListDB['WhereQuery']? "WHERE ". $CustomListDB['WhereQuery'] : "";
    $orderby= $CustomListDB['Order']? "ORDER BY ". $CustomListDB['Order'] ." $orderby" : "" ;
    $select= $CustomListDB['Select']? $CustomListDB['Select']: "*";
    $Selection=$ListDB-> GoFetch($CustomListDB['WhereQuery'] ." $orderby", $select);
  } else { // Make list using 'list_list' table
    $orderby="ORDER BY list_order $orderby";
    $Selection=$ListDB-> GoFetch("WHERE cluster='". $array['field_list'] ."' $orderby"); 
  }
  
  //Chose list in certain cluster which active (active not null)
  
    foreach($Selection as $L){
      // Decided if this option should be the default selection
      if(!empty($L['default']) || $default==$L[$Val]){ $Selected="selected=selected"; } else {unset($Selected); }

      //Draw
      $Options = $Options ."<option value='". $L[$Val] ."' $Selected>". lan2var($L[$Opt]) ."</option>";

    }
  return $Options;
}

//Make full name form MySQL query
function FullName($Arr){
  $Name= $Arr['prefix'] ." ".$Arr['FName'] ." ". $Arr['LName'];
  return $Name;
}

//Log Patient
function LogPatient($PID){
  $Pclass= New GoodBoi('com_gita_patient');
  $Px=$Pclass -> GoFetch("WHERE patientid='". $PID ."'");
  $_SESSION['Patient']=$PID;
  $_SESSION['PName']=FullName($Px[0]);
  $_SESSION['Px']=json_decode($Px[0]);
}

//Seperate Name in Post
function POSTName($array){
  if(array_key_exists("FullName",$array)){
    $Name=NameSep($array['FullName']);
    unset($array['FullName']);
    $array['FName']=$Name['FName'];
    $array['LName']=$Name['LName'];
  }
  return $array;
}

//Insert to Array to specific location
function Pokeball($array,$key,$new,$Insert='Before'){
  // (Original array, Key mark, New data as array, inster 'Before' or 'After' the $key)
  foreach($array as $y=>$x){
    if ($Insert=='After'){ $Res[$y]=$x; }
    if($y==$key){ 
      foreach($new as $b=>$a){
        $Res[$b]=$a;
      }
     }
    if ($Insert=='Before'){ $Res[$y]=$x; }
  }
  return $Res;
}


function MakeOptions($x){
  //Make options from list
  //$x : ARRAY of fetched layout row
  $Option=new GoodBoi('list_list');
            $Options= $Option->GoFetch("WHERE cluster='". $x['field_list'] ."' AND active=1 ORDER BY list_order");
            foreach($Options as $x){
              $Datalist .="<option value='". $x['list_value'] .">". lan2var($x['list_name']) ."</option>";
            }
            return $Datalist;
}

function RefinePost($Additional=null,$Remove=null,$Base=null){
  // Refine $_POST, or any array [Usefull for preparing data to be shoved as whole into MySQL]
  /*
  $Additional = [ARRAY] automated to be added (key=>automaton type)
  $Remove = [ARRAY] data to be removed (key)
  $Base = [ARRAY] the processes array, (Default: $_POST)
  */

  $Base = $Base? $Base : $_POST;

  //-----Action!--------
  $_POST = array_map('strip_tags', $_POST); //STRIPPING
  $newera=$_POST;
  
  //-----Add additional registration input-----
  $Who=WhoAreYou();
  $Who=json_encode($Who);
  $Additional= array('register_date'=>Date2SQL(),'last_mod'=>Date2SQL(),"last_mod_by"=>$_SESSION['Person']); //Aditional values to record on DB on registration

  if($_GET['job']==1){ // New Patient specific additional data
    $Additional += array("registered_by"=>$_SESSION['Person'], 'registered_at'=>$SettingCurrentFacility);
  }

  $registered=array_merge ($newera,$Additional);
  $registered=POSTName($registered);
  $registered=RemahRemah($registered);
}


//Remove specific key from an array, suefull to cleaning up the $_POST before inserting into MySQL
		// $crumb = ARRAY The Proccesed Array
		// $Remover = ARRAY of string, all keys that EQUAL the string will be removed
		// $RemoverLike = ARRAY of string, all keys that CONTAIN the string will be removed 
function RemahRemah($crumb,$Remover=null,$RemoverLike=null){
  foreach($Remover as $x){
    unset($crumb[$x]);
  }
  foreach($RemoverLike as $x){
    foreach($crumb as $b=>$a){
      if(strpos($b, $x) !== false){
        unset($crumb[$b]);
      }
    }
  }
  return $crumb;
}


//Ressurect JSON into data (Array, Object) and all JSON inside the JSON inside the JSON...
//$Data = ARRAY. All JSON inside the array, or including those inside another JSON, will be ressurected
//$Keys = ARRAY. If defined, only the keys in this array ar resurrected, leaving other keys, whom might have other JSON, left dead as JSON corpses. Usefull if you want only specific keys to be converted while leave others json.
function PhoenixDown($Data,$Keys=null){
  foreach($Data as $y=>$x){
    if($Keys){ if(!in_array($y,$Keys)){ continue; } } // If $Keys defined, skip if the keys isn't found on the $Keys Array
    $Data[$y] = unJson($x);
    if(is_array($Data[$y])){
      $Data[$y] = PhoenixDown($Data[$y]);
    }
  }
  return $Data;
}

//Filter array within array with specific value in specific key
//Filter $Arr whom have $Key with value of $Val
//return an ARRAY containing the Array(s) mathching with the search criteria
function FilterArray($Arr,$Key,$Val){
  $Returned = array();
  foreach($Arr as $x){
    if($x[$Key]==$Val) { array_push($Returned,$x); }
  }
  return $Returned;
}

//Trim ARRAY of ARRAY $arr, leaving only key that is stated in ARRAY $key
//$arr = Array to be trimmed
//$key = Array of key name to be kept
function Hadouken($arr,$key){
  foreach($arr as $y=>$x){
    if(!in_array($y,$key)){
      unset($arr[$y]);
    }
  }
  return $arr;
}

//Update Array 1 by replacing avilable key in array 2
function ArrayUpdate($a1,$a2){
  foreach($a2 as $y=>$x){
    $a1[$y]=$x;
  }
  return $a1;
}

//ARRAY of ARRAY. Change index numb of item based on that item['someKey']
// Example case : after fetching MySQL data, assign the primary column as the index insetad of row
// CAUTION : The key must be contain unique value on each item
// $Array = ARRAY to be proccesed (array1,array2,...)
// $Key = Which key to be used as index?
// CALLBACK : ARRAY (array1[$Key]=>array1, array2[$Key]=>$array2)
function Organize($Array,$Key){
  foreach($Array as $y => $x){
    $a[$x[$Key]] = $x;
  }
  return $a;
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
  echo "<div class='w3-center w3-card w3-panel w3-red'><h3>$lanError - $Title</h3><p>$Content</p></div>";
   
}

function OkDialog($Title,$Content){
  Global $lanOK;
  echo "<div class='w3-center w3-card w3-panel w3-green'><h3>$lanOK - $Title</h3><p>$Content</p></div>";
   
}

function WarningDialog($Title,$Content){
  Global $lanWarning;
  echo "<div class='w3-center w3-card w3-panel w3-yellow'><h3>$lanWarning - $Title</h3><p>$Content</p></div>";
   
}

function Mark($a,$pre='Mark',$post=null){ //DEBUGGING TOOLS
  if(!$_SESSION['DeFlea']) { return; }
  $SID=rand();
  echo "<div id='$SID' class='w3-tiny w3-hover-pale-blue w3-card-4 w3-panel w3-pale-yellow w3-small mark'><h6>$pre </h6><p>";
  if(is_array($a)){
    var_dump($a);
  } else {
    echo $a ;
  }
  echo "</p><p>$post</p></div>";
}

function MarkA($a,$pre=null,$post=null){ //DEBUGGING TOOLS
  if(!$_SESSION['DeFlea']) { return; }
  $Mid=rand();
  echo "<div class='w3-card-4 '><button class='w3-button w3-block w3-left-align w3-light-green' onclick=BukaTutup('". $Mid ."')><strong>$pre </strong></button>";
  echo "<div id='". $Mid ."' class='w3-container w3-hide w3-pale-blue'><div>";
  if(is_array($a)){
    var_dump($a);
  } else {
    echo $a ;
  }
  echo "</div>";
  echo "<div> $post</div></div></div><br />";
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

function AangList($array,$ulId){
  //(Array of list('Main'[main text], 'Sub'[sub text], 'Aang'[avatar pic source], 'URI'[Edit URL], 'OK' [OK URL])
  //mark($array, "AANGL ISTA RRAY");
  echo "<div class=''>
        <ul class='w3-ul w3-card-4' id=$ulId>";
  foreach($array as $y=>$x){
    // BUtton 1
    echo "<li id='" . $ulId . "_li_" .$y. "' class='w3-bar w3-white w3-hover-lime'>";
    if(!empty($x['OK'])){ echo "<span class='w3-bar-item w3-buttonw3-xlarge w3-right'><a href=". $x['OK'] ." ><i class='material-icons'>person</i></a></span>"; }
    // Button 2
    if(!empty($x['URI'])){ echo "<span class='w3-bar-item w3-buttonw3-xlarge w3-right'><a href=". $x['URI'] ." ><i class='material-icons'>menu</i></a></span>"; }
      if(!empty($x['Aang'])){ echo "<img src='Media/Korra/". $x['Aang'] ."' class='w3-bar-item w3-circle w3-hide-small' style='width:85px'>"; }
      echo "<div class='w3-bar-item'>";
       // Click URL
    if(!empty($x['Click'])){ echo "<a class='w3-large' href=". $x['Click'] ." >". $x['Main'] ."<br></a>"; }

        
       echo "<span>". $x['Sub'] ."</span>
      </div>
    </li>";
  }
  echo "</ul> </div>";
}
//---------------The Main Drawer---------------------------
//Turn ARRAY and all ARRAYs within it into STR and ECHO them
//$Draw = ARRAY or STR to be drawn
function Gardevoir($Draw){
    if(is_array($Draw)){
    foreach($Draw as $x){
      Gardevoir($x);
    }
    
  } else {
    echo $Draw;
  }
}

// Draw Diagnosis list on patient profile/visit data
function DXFList($Dx,$Job='view'){
  $Ran = rand(1,1000);
  $Dx=DxEater($Dx);
  foreach ($Dx as $y=>$x){
    $lid = 'Li_'. $Ran .'_' . $y;
    $Class = $x['Susp']? 'w3-container w3-padding-small w3-hover-orange w3-text-orange w3-hover-text-white' : 'w3-container w3-padding-small w3-hover-blue w3-text-blue w3-hover-text-white';

    $PSxPIList .= "<li class='$Class' onclick=BukaTutup('$lid')>";

    // Drawing the displayed diagnosis along with their attributes if exist
   if($x['Susp']){ $PSxPIList .= 'Suspect '; }
   if($x['dx']){ $PSxPIList .= $x['dx']; }
   if($x['location']){ $PSxPIList .= ", ". $x['location'] ; }
   if($x['type']){ $PSxPIList .= " <span class=connector> type </span>". $x['type']; }
   if($x['grade']){ $PSxPIList .= " <span class=connector> grade </span>". $x['grade']; }
   if($x['stage']){ $PSxPIList .= " <span class=connector> stage </span>". $x['stage']; }
   if($x['causa']){ $PSxPIList .= " <span class=connector> et causa </span>". $x['causa']; }
   if($x['Thisnote']){ $PSxPIList .= ", ". $x['Thisnote']; }
   
   //Close button only appear ig job is edit or reg
   if(in_array($Job,array('edit','reg'))){
      $PSxPIList .= "<span class='w3-right-align w3-text-red rem'> [X]<script>$('.rem').on('click', function() {
        $(this).parent().addClass('w3-red');
        $(this).parent().remove();
      });</script></span>";
    }

    $PSxPIList .= '<span class="jonson" hidden="">';
    $PSxPIList .= json_encode($x);
    $PSxPIList .= '</span>';
    $PSxPIList .= "<div id='$lid' class='w3-container w3-hide w3-white'>". walkthrough($x['walkthrough']) ."</div>";
    $PSxPIList .= "</li>";
  }
  return $PSxPIList;
}

// Draw Planning list on patient profile/visit data
//$Sx = The Data (soap_subject, DXH, PXH,...)
//$Mode = STR, either 'Sym' for symptmp, 'Dx' for Diagnosis, 'Med' for Medicine
function SymList($Sx,$Mode,$Job='view'){
  $Sx = DxEater($Sx,$Mode);
  mark($Sx,"SX");

  switch ($Mode) {
    case "Sym":
      $Display=array(
        array("","symptomp"," "),
        array($GLOBALS['lanSymtompSep1'] ." ","location",", "),
        array($GLOBALS['lanSymtompSep2'] ." ","reffered_pain",", "),
        array($GLOBALS['lanSymtompSep3'] ." ","duration",", "),
        array($GLOBALS['lanSymtompSep4'] ." ","frequency",", "),
        array($GLOBALS['lanSymtompSep5'] ." ","quality",", "),
        array($GLOBALS['lanSymtompSep6'] ." ","worsened_by",", "),
        array($GLOBALS['lanSymtompSep7'] ." ","relieved_by",", "),
        array($GLOBALS['lanSymtompSep8'] ." : ","Thisnote",""),
      );
    break;
    case "Dx":
      $Display = array (
        array("","dx"," "),
        array(" ","location",", "),
        array($GLOBALS['lanType'] ." ","type",", "),
        array($GLOBALS['lanGrade'] ." ","grade",", "),
        array($GLOBALS['lanStage'] ." ","stage",", "),
        array($GLOBALS['lanCausa'] ." ","causa",", "),
        array($GLOBALS['lanNotes'] ." : ","Thisnote",", ")
      );
    break;
    case "Med":
      $Display = array (
        array("[","type","] "),
        array("","name"," "),
        array("","form_n",", "),
        array(": ","qday"," "),
        array("&times ","qtt"," "),
        array(" ","form"," "),
        array(" ","adm"," . "),
        array(" ","rule"," "),
        array(" (","extra",") "),
      );
    break;
  }
  

  $Arg = array('listIdKey'=>'id','idPrefix'=>'symp_','preClass'=>'connector');

  $Arg['liClassSusp'] = 'w3-container w3-padding-small w3-hover-orange w3-text-orange w3-hover-text-white'; 
  $Arg['liClass']  = 'w3-container w3-padding-small w3-hover-blue w3-text-blue w3-hover-text-white';

  if(in_array($Job,array('edit','reg'))){
    $Arg['removeButton'] =  $Arg['hiddenVal'] =  $Arg['walkthrough'] = TRUE;
  }
  mark($Sx,"SYMPTOMP $$");
  $Symptomp = JSON2List($Sx,$Display,$Arg);
  mark($Symptomp,"SYMPTOMP");

  foreach ($Symptomp as $x){
      //Close button only appear ig job is edit or reg
      $list .= $x;

  }
return $list;
}



function AllergiesList($All,$Job='view'){
  $Ran = rand(1,1000);
  $All=UnJson($All);
  foreach ($All as $y=>$x){
    $lid = 'Li_'. $Ran .'_' . $y;
    $x=UnJson($x);
    $x=UnJson($x);
    $Class = $x['Susp']? 'w3-container w3-padding-small w3-hover-orange w3-text-orange w3-hover-text-white' : 'w3-container w3-padding-small w3-hover-blue w3-text-blue w3-hover-text-white';

    $PSxPIList .= "<li class='$Class' onclick=BukaTutup('$lid')>";

    // Drawing the displayed diagnosis laong with their attributes if exist
   if($x['Susp']){ $PSxPIList .= 'Suspect '; }
   if($x['all']){ $PSxPIList .= $x['all']; }
   if($x['reaction']){ $PSxPIList .= " ==> ". $x['reaction'] ; }
   if($x['Thisnote']){ $PSxPIList .= " ,". $x['Thisnote']; }
   
   //Close button only appear ig job is edit or reg
   if(in_array($Job,array('edit','reg'))){
      $PSxPIList .= "<span class='w3-right-align w3-text-red rem'> [X]<script>$('.rem').on('click', function() {
        $(this).parent().addClass('w3-red');
        $(this).parent().remove();
      });</script></span>";
    }

    $PSxPIList .= '<span class="jonson" hidden="">';
    $PSxPIList .= json_encode($x);
    $PSxPIList .= '</span>';
    $PSxPIList .= "<div id='$lid' class='w3-container w3-hide w3-white'>". walkthrough($x['walkthrough']) ."</div>";
    $PSxPIList .= "</li>";
  }
  return $PSxPIList;
}

//Insert articels
//(Walktrought ID) UNDER CONSTRUCTION
function walkthrough($WID){
  return "[Diagnosis Info cooming soon]<br /> id is $WID";
}

// Display Age with year/month
// ($DOB=Date of birth, $Full=Display as full year-month?)
function AgeText($DOB,$Full=true){
  $birthday = new DateTime($DOB);
  $diff = $birthday->diff(new DateTime());
  $months = $diff->format('%m') + 12 * $diff->format('%y');
  $years = $diff->format('%y');
  if($Full){
    return $years ." ". $GLOBALS['lanYear'] ." ". ($months-($years*12)) ." ". $GLOBALS['lanMonth'];
  } else {
    if($months<=12){ 
      return $years ." ". $GLOBALS['lanYear']; 
    } else {
      return $months ." ". $GLOBALS['lanMonth'];
    }
    
  }
}

//Just like symList, but for medicine
//$RawData : The meidicine data
//$Job : 'edit' or 'view'
////ARGUMENTS////
//Style = List display style : 'inLine' displayed in one line | 'R' displayed like in presception paper
function MedListGenerator($RawData,$Job,$Arguments=array('Style' => 'R')){
  $Data = UnJson($RawData);
  //#1. Append medicine's data to the $Data
  $Data = AppendMed($Data);

  //#2. Prepare some variable
  switch($Job){
    case 'edit': // Add Remove and Edit butoon on Job Edit
    $remAndEdit = "<span class='w3-right-align w3-text-red w3-tiny rem' onclick=remButton($(this).parent().attr('id'))>[×]</span>
    <span class='w3-right-align w3-text-red w3-tiny edit' onclick=editButton($(this).parent().attr('id'))>[Edit]</span>";
    break;
  }

  //#3. Draw List
  foreach ($Data as $y => $x){
    
    
    foreach( $x['comp'] as $c){
      $compUnit = $x['comp']['prosent']? "%" : "mg";
      $compList .= "<li class='w3-small'>". $x['comp']['name'] ." ". $x['comp']['amount'] ." $compUnit</li>";
    }
    
    $medlist .= "
                    <li id=medlist_". $x['id'] ." medId='". $x['id'] ."' onclick=BukaTutup('detail_med_$y') class='w3-container w3-padding-small w3-hover-blue w3-text-blue w3-hover-text-white'>
                      <span class='w3-xlarge'>R/</span>
                      <div>
                          <strong>". $x['name'] ."</strong> 
                          ". $x['form_n'] ." 
                          No ". $x['No'] ."</div>
                      <div>
                          <span class='w3-large'>∫</span>
                          ". $x['qday'] ."
                          <span class='w3-small'>dd</span> 
                          ". $x['qtt'] ." 
                          ". $x['form'] ." 
                          ". $x['adm'] ." . 
                          ". $x['rule'] ." 
                          (". $x['extra'] .")</div>
                      <div></div>
                      $remAndEdit
                      <div class='jonson' hidden=''>$RawData</div>
                      <div id='detail_1' class='w3-card w3-hide w3-light-grey'>
                          <div class='w3-panel w3-row'>
                              <div class='w3-col m4'>". $x['type'] ."</div>
                              <div class='w3-col m4'>
                                  <ul class='w3-ul'>
                                      $compList
                                  </ul>
                              </div>
                              <div class='w3-col m4'></div>
                          </div>
                      </div>
                   </li>";
  }

  return $medlist;
}

//Append Medicine data to medication list 
function AppendMed($Data){
  $MedTable = new GoodBoi('com_gita_medicine');
  foreach($Data as $y => $x){
    $MedData = $MedTable -> GoFetch("WHERE id = '". $x['id'] ."'","name, comp, type, form_n");
    $Data[$y] += $MedData[0];
    $Data[$y]['comp'] = UnJson( $Data[$y]['comp'] );
  }
  return $Data;
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
 [\\\\\\\\\\\{*}:::<===============  FILE HANDLER ================-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/

// Image Upload Checker
function UploadFiles($File,$Allowed,$Dir=null){
  // (The input file id, array of allowed file extension, diresctory)
  $Error=array();
  $MediaDir= $GLOBALS['settingDirMedia'] ."/";
  $Dir = empty($Dir)? $MediaDir . $GLOBALS['settingDirDefault'] ."/" : $MediaDir . $Dir;
  $Dir = $Dir . basename($_FILES[$File]["name"]);
  $FileType = strtolower(pathinfo($Dir,PATHINFO_EXTENSION));
  if(isset($_POST["submit"])) {
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES[$File]["tmp_name"]);
    if($check === false) { array_push($Error, "Error1"); }
  }
  // Check if file already exists
  if (file_exists($Dir)) { array_push($Error, "Error2"); } 
   // Check file size
  if ($_FILES[$File]["size"] > 1024000) { array_push($Error, "Error3");  }
  // Allow certain file formats
  if(in_array($FileType, $Allowed )) { array_push($Error, "Error4"); }  


  if (!empty($Error)) {
    return "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          return "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
      } else {
          return "Sorry, there was an error uploading your file.";
      }
  }
}




/*
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
  $data = @json_decode($str);
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
    $Kayuing=array('SDesc'=>$a , 'Culpirt'=>$l['IP'], 'CBrowser'=>$l['Browser'], 'CPort'=>$l['Port'], 'Victim'=>$l['sIP'], 'Vport'=>$l['sPort'], 'VURI'=>$l['URI'], 'Command'=>$b, 'Error'=>$d, 'User'=>$_SESSION['Person'], 'UserN'=>$_SESSION['UserN'], 'Name'=>$_SESSION['Name'], 'ULevel'=>$ULevel, 'UGroup'=>json_encode($_SESSION['UGroup'])); 
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
 [\\\\\\\\\\\{*}:::<===========  Modules  =================-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/

//-----------------Call Module With Arguments-----------------------------
function Module($Mod,$Argument=null){
  include "Modules/$Mod/modules.php";
}

//-----------------Call Modules In Certain Posititon-----------------------------
function Modules($Position=null){
  if(empty($GLOBALS['GoodModules'])){
    $GLOBALS['GoodModules'] = new GoodBoi('modules_deployment');
  }
  $Modules=$GLOBALS['GoodModules']->GoFetch("WHERE position='". $Position ."' ORDER BY short ASC");
  foreach($Modules as $x){
    Module($x['module'],$x['arguments']);
  }
}

/*
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


██████╗ 
██╔══██╗
██████╔╝
██╔═══╝ 
██║     
╚═╝     

************************************************************************************************
             /<                                             
            /<                                              
  |\_______{o}----------------------------------------------------------_
 [\\\\\\\\\\\{*}:::<===================  PHRASE   =================-       >
  |/~~~~~~~{o}----------------------------------------------------------~
            \<
             \<
              \>
************************************************************************************************
*/

function SymptompPhraser($x,$space=null){
  // (Array of GoFetch from Symptomp DB, seperator (if null, use default))
  if (empty($space)){
    $n= -1;
    foreach($x as $b=>$a){
      if(!empty($a) AND $b!='id'){
        $symtpomp .= $GLOBALS["lanSymtompSep$n"] . " " . $a . " ";
      }
      $n++;
    }
  return $symtpomp;
  } else {
    return $x['symptomp'] ." $space ". $x['location'] ." $space ". $x['reffered_pain'] ." $space ". $x['duraiton'] ." $space ". $x['frequency'] ." $space ". $x['qualitiy'] ." $space ". $x['worsened by'] ." $space ". $x['relieved_by'];
  }
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
  session_start();
  $userlist=new GoodBoi("staff_list");
  $x=$userlist->GoFetch("WHERE usrid='$User'");
  $_SESSION['Person']=$User;
  $_SESSION['UserN']=$x[0]['UserName'];
  $_SESSION['Name']= FullName($x[0]);
  $_SESSION['ULevel']=$x[0]['UserLevel'];
  $_SESSION['UGroup']=deserialization($x[0]['UserGroup']);
}

function Logout(){
  session_destroy();
}

function LogUser($Userclass=null){ // Update the Last IP and Last activity of user (User ID, Class that linked to staff_list db)
  if(empty($_SESSION['Person'])) { return; }
	$Who=WhoAreYou();
	$Who=json_encode($Who);
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

//--- because i'm to lazy to memorize and to type the whole code everytimes
function GetOwnURL(){
  return htmlspecialchars( $_SERVER['PHP_SELF'] );
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

  
  public function GoFetch ($selection,$Method="*"){ 
    // selection : STR The "WHERE" query, can also be ARRAY of (column=>searched value)  
    if(is_array($selection)){
      
      foreach ($selection as $y=>$x){
        if(!$syn) { $syn = "WHERE ". $y ."='". $x ."'";} else { $syn .= " AND ". $y ."='". $x ."'"; }
        
      }
      $selection = $syn;
    }
    $ewe = SniffButt();
    $query="SELECT ". $Method ." FROM ". $this->table ." $selection";
   
    $result = $ewe->query($query);
    //Debug
    if(!empty($_SESSION['DeFlea'])){  echo "<div class='w3-card-4 w3-purple w3-panel backtrace ' >". debug_backtrace()[1]['function'] ."</div>"; Mark($query,__CLASS__ ." ==> ". __FUNCTION__,"Error :". $ewe->error ); }
    //Debug
    $row = $result->fetch_all(MYSQLI_ASSOC); 
    if(!empty($_SESSION['DeFlea'])){  markA($row,"Returned Row(s) : ". count($row)); }
   return $row;
  }



  public function GoBark ($data){ // Method to INSERT data, $data is an array (Column as Key, and Values as Value))
    $bun = SniffButt();
    $dataesc= Blissey($data);
    $filling=array2csv($dataesc,"'",null,1);
    $sausage= "INSERT INTO ". $this->table ." (". $filling['Key'] .") VALUES (". $filling['Val'] ."); ";
    //Debug
    MarkA($sausage, __CLASS__ . "++++" . __FUNCTION__);
   //Debug
    $bun->query($sausage) ;
    Mark($bun->error, "Wan! Wan! Wan!", $sausage);
    return $bun->insert_id;
  }


  public function GoCount($a){
    $RowCount=$this->GoFetch($a,"COUNT(*) as Total");
    //Debug
    
   if(!empty($_SESSION['DeFlea'])){ Mark("Row Count is : [". $RowCount[0]['Total'] ."] for $a"); } 
   //Debug
      
   return $RowCount[0]['Total'];
  }

  public function GoBurry($data,$selection){ //Method to Update SQL data (array(column=>new value,"WHERE bla bla bla"[ can also be as array(column=>criteria val)])
    $bun = SniffButt();
    $dataesc= Blissey($data);
    if(is_array($selection)){
      $syn = "WHERE ";
      foreach ($selection as $y=>$x){
        $syn .= $y ."='". $x ."' ";
      }
      $selection = $syn;
    }
    $filling=array2arrow($dataesc,"="," , ");
    $sausage= "UPDATE ". $this->table ." SET $filling $selection";
    //Debug
   //Debug
   DeFlea($sausage, __CLASS__ . "++++" . __FUNCTION__);
   //Debug
   if ($bun->query($sausage) === TRUE) {
    return $bun->insert_id;
  } else {
    mark($bun->error,"Kaing Kaing");
  }
   //Debug

  
   
   

  }

}
/////////////////////////////////////////////////////////////////




















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
  
    function __Construct($Error,$FormId,$MyClass=null,$MyStaff=null,$Arguments=array(null,null,null,null,null,1)){
      // (Error language variable, Form ID, GoodBoi class layout, staff, Display error?, Run Check error0 array(Input,Confirmation), error4 (UserID,New Password, Old Password, Password Field ID), error1, error2, error3)
      $E0=$Arguments[0];
      $E1=$Arguments[1];
      $E2=$Arguments[2];
      $E3=$Arguments[3];
      $E4=$Arguments[4];
      $ShowError= empty($Arguments[5])? 1 : $Arguments[5] ;

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
      $Required=$this->MyClass->GoFetch("WHERE form_id = '". $this->FormId ."' AND field_attrib LIKE '%\"required\":\"true\"%'","field_id, field_label");
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
      $Validation=$this->MyClass->GoFetch("WHERE form_id = 'gita_login_signup' ","field_id, field_label, field_type, field_attrib");
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
            $x['field_attrib'] = UnJson($x['field_attrib']);
            $x['field_validation'] = $x['field_attrib']['pattern'];
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
  public $listfield; // ARRAY of field that not datalist type, but should be treated as datalist
   
  function __Construct($DataID,$OriTab,$Data,$listfield=null,$Method='Edit',$TargetClass=null,$Auto=TRUE){
    /*
    $DataID : Target Table's ID column (e.g. usrid for staff_list)
    $OriTab : Target Table name (e.g. staff_list)
    $Data : ARRAY of submited Data as (column=>value)
    $listfield : ARRAY of field that not datalist type, but should be treated as datalist (For Adding to list_list or cust table)
    $Method : (New, Edit), Submited Data in array as column=>value
    $TargetClass : Object GoodBoi to target table
    $Auto : If FALSE, the constructor won't do the inserting. Inserting job must manually declared, default is TRUE
    */
    
   $this->GoodBoi_Version = new GoodBoi("snorlax"); // Connect to snorlax DB
   if(empty($TargetClass)){ $this->GoodBoi_Target = new GoodBoi($OriTab);  } else {$this->GoodBoi_Target=$TargetClass;  } //connect to target DB
   $CulpirtInfo = json_encode(WhosKnock());
   $Culpirt = empty($_SESSION['Person'])? 0 : $_SESSION['Person'] ;
   $this->listfield = $listfield;
   $this->TargetID = $DataID;
   $this->TargetTab = $OriTab;   
   $this->LaxIncense = array('timest'=>Date2SQL(),'culpirt'=>$Culpirt, 'culpirt_info'=>$CulpirtInfo, 'facility'=>$GLOBALS['SettingCurrentFacility'],'original_table'=>$OriTab, 'edited_id'=>$Data[$DataID]);
   $this->Data = $Data;
   mark($this->Data,"THIS DATA");
   Mark($Method,"SNORLAX");  //========================== DEBUG===========================
   if($Auto){
      switch ($Method){
        case 'Edit':
          $this->EVs();
          break;
        case 'New';
          $this->IVs();
          break;
      }
    }
  }

  function EVs(){
    Mark("SNORLAX EV"); //========================== DEBUG===========================
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
    $BURK= new AddList ($this->Data, $this->listfield);
  }

  function IVs(){
    Mark("SNORLAX IV"); //========================== DEBUG===========================
    /*
    FLOW
    1. Add version=>1 to submited data, insert into target table
    2. Make new array and serialize [DataOne] with key all of the Submited Data Key and value = 1, return as array edited_data=>DataOne, version=>current + Additional data (LaxIncense) and Inster into Subversion table
    
    */
     //[1]
     $Insert = $this->Data + array('sversion'=>1); // Detect new row's ID: Is the ID supplied in the data? (Key of 'Target ID' exist in the supplied array), if yes, use that. If No, get the data automatically
     if(array_key_exists($this->TargetID,$this->Data)){
      $this->GoodBoi_Target->GoBark($Insert);
      $Pokeball= $this->Data[$this->TargetID] ;
     } else {
      $Pokeball= $this->GoodBoi_Target->GoBark($Insert); 
      }
     $GLOBALS['NewDex']=$Pokeball;
    //[2]
    $DataOne=array();
    foreach($this->Data as $y=>$x){
      $DataOne += array($y=>1); 
    }
    $DataOne= json_encode($DataOne);
    $VHC= array('edited_data'=>$DataOne,'version'=>'current','edited_id'=>$Pokeball) + $this->LaxIncense;
    $SVNNewID = $this->GoodBoi_Version->GoBark($VHC);
    $BURK= new AddList ($this->Data, $this->listfield);
    mark($Pokeball,"SNORLAX IV NEW ID ");
    return $Pokeball;
  }

  //1. Fetch column list with '1' [ColumnList] in Subversion DB where version=current AND edited_id= current edited id (ATR1) AND original_table = edited table (ATR2) [CurentVerRow].
  function DayCareCouple(){
    $Gluttony= $this->GoodBoi_Version->GoFetch("WHERE version='current' AND edited_id='". $this->Data[$this->TargetID] ."' AND original_table='". $this->TargetTab ."' LIMIT 1" );
    $this->PrevVersionID = $Gluttony[0]['id']; //ID of the Gluttony
    $ColumnList=array(); // set up the ColumnList array
    $SGluttony = json_decode($Gluttony[0]['edited_data']); // Make array of the "edited_data"
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
    $Returned=json_encode($Returned);
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
    $Snore= json_encode($Snore);
    return array('edited_data'=>$Snore,'version'=>'current') + $this->LaxIncense;
  }

  //The actual DB wiritng process
  function PulverizingPancake($Ditto,$Snore){
    mark($this->Data,"FATA ID");
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
  protected $Arguments;
  protected $ulId; // <ul> id
  public $Gardevoir; //Array of the final list draw

  // (GoodBoi Class, [ARGUMENT])
  //Arguments
  //// hiddenDiv = text to be inserted into a hidden div (not showed, but can be used by search filter)
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
      $this->Arguments = $Argument;

      $this->ColumnMain=$ColumnMain;
      $this->Header=$E=Array2Array($ColumnLabelObj->GoFetch("WHERE $HeadingWhereCol ='$HeadingWhereVal'"),'field_id','field_label');
      $this->GoodBoi=$GoodBoi;
      $this->DefaultSearchColumn=$DefaultSearchColumn;
      $this->ColumnLabelObj=$ColumnLabelObj;
      $this->FormID=$HeadingWhereVal;
      $this->TID=$TID;

      //Name Colom to array
      $this->NameColumn=explode(",",$NameColumn);

      $this->ulId = 'list_'. rand();

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
    Gardevoir($this->SearchList());
    $this->DrawList();
  }

  function DrawList(){
    $EURI=$_GET;
    $EURI['job']=4;
    $EURI = GetGET($EURI);
    $CURI=$_GET;
    $CURI['job']=3;
    $CURI = GetGET($CURI);
    foreach($this->Query as $x){
      $y=$x[$this->TID];
      $yoyo[$y]['Main'] = in_array('LName',$this->Arguments['ContentMain'])? FullName(Hadouken($x,$this->Arguments['ContentMain'])): array2csv(array_map('lan2var',Hadouken($x,$this->Arguments['ContentMain'])))['Val'] ;
      $yoyo[$y]['Sub']= array2csv(array_map('lan2var',Hadouken($x,$this->Arguments['ContentSub'])))['Val'];
      $yoyo[$y]['Hidden']= array2csv(array_map('lan2var',Hadouken($x,$this->Arguments['hiddenDiv'])))['Val'];
      $yoyo[$y]['URI']= htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?$EURI" . "dataid=". $x[$this->TID];
      $yoyo[$y]['Click']= htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?$CURI" . "dataid=". $x[$this->TID];
      switch ($this->TID){
        case 'patientid':
          $yoyo[$y]['OK']= htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_patient&job=5&dataid=". $x[$this->TID];
          break;
      }
      //Menyamakan persepsi antara tabel pasien dan staff (Beda nama kolom)
      if($x['Aang']) { $x['photo'] = $x['Aang']; }
      if($x['Sex']) { $x['sex'] = $x['Sex']; }

      if(empty($x['photo'])){
        switch ($x['sex']){
          case '$lanMale':
          $yoyo[$y]['Aang']='def_male.png';
          break;
          case '$lanFemale':
          $yoyo[$y]['Aang']='def_female.png';
          break;
          case '$lanAlien':
          $yoyo[$y]['Aang']='def_alien.png';
          break;
          case '$lanUnidentified':
          $yoyo[$y]['Aang']='def_unknown.png';
          break;
        }
      }
      
    }
    //$Draw=ZebraList($this->Query,$this->NameColumn,$this->Header,array(6=>$this->TID,5=>$URI));
    
    $Draw = AangList($yoyo,$this->ulId);
    $this->Gardevoir=$Draw;
    if(Empty($this->Query)){
      echo $GLOBALS['lanNoResult'];
    }
  }

  function SearchList(){
    $ulId = $this->ulId;
    if(!empty($this->DefaultSearchColumn)){
      //Draw search form
      $URI = GetGET();
      
      $Form= "<div class='w3-card w3-white'><div class='w3-row w3-panel'><form  action=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?$URI method='GET' class='Search'>";
      unset($_GET['listcrit']);
      unset($_GET['listcolumn']);
      $Form .= "<div class='w3-cell  w3-cell-bottom' style='width:75%'><span><label for='listcrit' class='w3-label'>". $GLOBALS['lanSearch'] ."</label><input name='listcrit' class='w3-input' id='listcrit' type=search onkeyup=\"listFilterHide('$ulId','listcrit')\"></span></div>";

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

      $Form .= "<div class='w3-cell w3-cell-bottom' style='width:20%'><span><select name=listcolumn class='w3-select'>";
      $Form .= "<option value=ALL selected>". $GLOBALS['lanAll'] ."</option>";

      // Making the search column list
      $rpl= csv2csv($this->DefaultSearchColumn);
      $Label=$this->ColumnLabelObj->GoFetch("WHERE form_id='". $this->FormID ."' AND field_id IN (". $rpl .")","field_label, field_id");
      foreach ($Label as $x){
        $Form .= "<option value=". $x['field_id'] .">". lan2var($x['field_label']) . "</option>";
      }

      $Form .= "</select></span></div>";
      foreach ($_GET as $y=>$x){
        $Form .= "<input type=hidden name=$y value=$x>";
      }
      $Form .= "<div class=' w3-cell  w3-cell-bottom' style='width:5%' ><span> <i class='w3-button material-icons w3-large' class='submitButton'>search</i></span></div></div>";
      $Form .= "</form></div>";
      return $Form;
     }
    }

    function Gardevoir(){
      
      Gardevoir($this->Gardevoir);
    }
}
*/
/*
=======================================================================================
██╗     ██╗███████╗████████╗██╗███╗   ██╗ ██████╗ 
██║     ██║██╔════╝╚══██╔══╝██║████╗  ██║██╔════╝ 
██║     ██║███████╗   ██║   ██║██╔██╗ ██║██║  ███╗
██║     ██║╚════██║   ██║   ██║██║╚██╗██║██║   ██║
███████╗██║███████║   ██║   ██║██║ ╚████║╚██████╔╝
╚══════╝╚═╝╚══════╝   ╚═╝   ╚═╝╚═╝  ╚═══╝ ╚═════╝ 
=======================================================================================
"The Imperial sure love their damn list!"

Class to display a list (e.g. Patient list, Staff list) in many style

All the method to make list are CALLBACK that return ARRAY of the list items (Opening, Item1, Item2, ... , Closure) with each items contain string
Draw it with METHOD 'Draw' for filter box placement

List of METHOD :

SUPPLEMENT

  FetchArray($MySQL): [CALLBACK] (AUTORUN if 'MySQL' Arguments passed on construct)
        Prepare data ARRAY by fetching from MySQL DB.
        The result will be returned and used to populate class's 'Array' Property on Autorun
        $MySQL : ARRAY of MySQL fetch data configuration, see constructor's Arguments for detail

  PrepareArray($Raw,Need) [CALLBACK]:
        Format ARRAY to be ready to be drawn
        return the refined ARRAY
        $Raw is the raw array
        $Need is ARRAY ('main'=>The key for main text, 'sub'=>..., 'hidden'=>..., ....)

  FilterList: [CALLBACK]
        Make and return 'Filter' box HTML

  Draw($List):
        Draw the list and the search column
        
LIST STYLE

  [Aang] : List with Avatar (Picture), hence, the name
  -------------------------------------------------------------
  |                            [button3] [button2] [button1]  |
  |  [picture]   [main]                                       |
  |              [sub]                                        |
  -------------------------------------------------------------
  Aang($picPath,$noPic,$array,$Class)
    $picPath = path to pictures directory in ('Media' directory), default is 'Korra'
    $noPic = How picture will be generated if data has no picture (refer to METHOD PicGenerator)
    $array = The array to be drawned, if empty, use class's Array PROPERTY instead
    $Class = ARRAY of class option 'color', 'hover'
  
                                                  
*/

class Imperial{
  public $Array; // Main array
  public $Arguments;
  public $keyID; //Which key is the ID
  public $ulId; // <ul> id
  public $RefinedArray; // Refined array that ready to be drawed

  //////////////////////////////////////////CONSTRUCTOR//////////////////////////////////////////////
  /* 
  $Array = Main Array ('id1'=>list1,'id2'=>list2) => list is ASSOC ARRAY of (
                                                                'main'=>Main text,
                                                                'sub'=> Sub text,
                                                                'hidden'=> Hidden text (For filtering and other purpose),
                                                                'picture'=> Image filename to be showed ,
                                                                'onClick' => Link on click,
                                                                'dropDown' => HTML that will be hidden at first, but displayed when clicked,
                                                                'button1' => Button 1 : ARRAY ('DOM'=>HTML element to be distplayed, 'link' => Link),
                                                                'button2' =>  Button 2 : ARRAY ('DOM'=>HTML element to be distplayed, 'link' => Link),
                                                                'button3' => Button 3 : ARRAY ('DOM'=>HTML element to be distplayed, 'link' => Link, 'toolTip'=>Tool Tip)
                                                                ') 
  $Arguments = Argument Array => ( 'MySQL' => for fetching data from MySQL, if declared, fetching from MySQL will repace main array . ARRAY (
                                                                                                                                            'table'=>MySQL Table,
                                                                                                                                            'condition'=>WHERE QUERY
                                                                                                                                            'select'=>SELECT array (column1, column2), empty mean all (*)
                                                                                                                                            'id' => Column contain primary key
                                                                                                                                            'main' => ARRAY ('del'=>Delimiter (seperator string), 'data'=>INDEXED ARRAY of column to be set as main text),
                                                                                                                                            'sub' => ARRAY ('del'=>Delimiter (seperator string), 'data'=>INDEXED ARRAY of column to be set as sub text),
                                                                                                                                            'hidden' => INDEXED ARRAY of column to be set as hidden text,
                                                                                                                                            'picture' => column to be set as picture,
                                                                                                                                            'onClick' => $_GET to be set as onClick link,
                                                                                                                                            'dropDown' => column to be set as Drop Down,
                                                                                                                                            'button1','button2','button3' => $_GET to be set as link for corespondenting button,
                                                                                                                                              ),
                                  'heading' => Heading text, 
                                  'filter' => Include search area? where? (top,bottom),   
                                  'picDir' => directory path of pictures in (Media/...).
                                  'listId' => ID of the <ul>   
                                  )                                                    
  */
  
  function __Construct($Array,$Arguments=null){
      $this->Array = $Array;
      $this->Arguments = $Arguments;
      $this->ulId = $Arguments['listId']? $Arguments['listId'] : rand(0,100);
      $this->RefinedArray = $Arguments['MySQL']? $this->FetchArray($Arguments['MySQL']) : $Array;
  }

  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //Populate main array from MySQL
  public function FetchArray($MySQL){
    $this->keyID = $MySQL['id'];
    $Tab = new GoodBoi($MySQL['table']);
    $Selector = $MySQL['select']? $MySQL['select']: "*";
    $this->Array = $Tab->GoFetch($MySQL['condition'],$Selector);
    $Data = $this->PrepareArray($this->Array,$MySQL);
    return $Data;
    
  }

  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //Format array to be ready to drawed
  //$Raw is the raw array
  //$Need is ARRAY ('main'=>The key for main text, 'sub'=>..., 'hidden'=>..., ....)
  public function PrepareArray($Raw,$Need){
    foreach($Raw as $y=>$x){
      
      //Temper certain data
        $x['dob'] = AgeText($x['dob']);


      $id=$x[$this->keyID];
      foreach($Need as $b=>$a){
        //mark($a,$b);
        switch($b){
          case 'main':
          case 'sub':
            if(in_array('LName',$a)){ 
              $Clean[$id][$b]['val']['fullName'] =  "aa" . FullName(Hadouken($x,$a['data']));
              unset($x['prefix']);
              unset($x['FName']);
              unset($x['LName']);
            } 
            
            foreach(Hadouken($x,$a['data']) as $o=>$v){
              $Clean[$id][$b]['val'][$o] =lan2var($v) ;
            }
            $Clean[$id][$b]['del'] = $a['del'] ;
            break;
          case 'hidden':
            array2csv(array_map('lan2var',Hadouken($x,$a['data'])))['Val'];
            break;
          case 'picture':
          case 'dropDown':
            $Clean[$id][$b] = $x[$a] ;
            break;
          case 'onClick':
            $Clean[$id][$b]="index.php?" . $a ."&dataid=$id" ;
            break;
          case 'button1':
          case 'button2':
          case 'button3':
            $a['link'] = "index.php?" . $a['link'] ."&dataid=$id" ;
            $Clean[$id][$b] =  $a ;
            break;
          default:
            continue;
            break;
        }
        
      }
    }
    return $Clean;
  }

  //
  
  // UNDER CONSTRUICTON
  // USe to modify $this->RefinedArray
  // $modKey = What key to be modified
  // $newArray = The New Array
  // $Arguments
  /////// Function = Function to be called to modify, if left null, no funciton will be called
  /////// Organize = STR Perform 'Organize' function with this STR as key
  /////// Which = Which part to be modified? (main/sub), default is 'main'
  public function RefineRefined($modKey,$newArray,$Arguments=array('Which'=>'main')){ 
			if($Arguments['Organize']) {$newArray = Organize($newArray,$Arguments['Organize']);}
			$Ref = $this->RefinedArray;
			foreach ($Ref as $y => $x){
        $Ref[$y][$Arguments['Which']]['val'][$modKey] = $Arguments['Function']? call_user_func($Arguments['Function'],$newArray[$Ref[$y][$Arguments['Which']]['val'][$modKey]]) : $Ref[$y][$Arguments['Which']]['val'][$modKey];
      }
      
      $this->RefinedArray = $Ref;
    }
      
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //Make list with avatar picture
  // -------------------------------------------------------------
  // |                            [button3] [button2] [button1]  |
  // |  [picture]   [main]                                       |
  // |              [sub]                                        |
  // -------------------------------------------------------------
  // $picPath = path to pictures directory in ('Media' directory), default is 'Korra'
  // $noPic = How picture will be generated if data has no picture (refer to METHOD PicGenerator)
  // $array = The array to be drawned, if empty, use class's Array PROPERTY instead
  // $Class = ARRAY of class option 'color', 'hover'
  public function Aang($picPath='Korra',$noPic=null,$array=null,$Class=array('color'=>'white','hover'=>'lime')){
    $ulId = $this->ulId;
    $array= $array? $array: $this->RefinedArray;
   

    //Opening
    $Items['__pre'] = "<div class=''>
                    <ul class='w3-ul w3-card-4' id=$ulId>";
    
    //Items
    $c=0;
    foreach($array as $y=>$x){
      $x['main'] = $this->Stringify($x,'main');
      $x['sub'] = $this->Stringify($x,'sub');
      
      $Items[$y] = "<li id='" . $ulId . "_li_" .$y. "' class='w3-bar w3-" . $Class['color'] . " w3-hover-" . $Class['hover'] . "'>";
      // Button 1
      if($x['button1']){ 
        $Items[$y] .= "<span title=". $x['button1']['toolTip'] ." class='w3-bar-item w3-buttonw3-xlarge w3-right'><a href=". $x['button1']['link'] ." >". $x['button1']['DOM'] ."</a></span>";
      }
      // Button 2
      if($x['button2']){ 
        $Items[$y] .= "<span title=". $x['button2']['toolTip'] ." class='w3-bar-item w3-buttonw3-xlarge w3-right'><a href=". $x['button2']['link'] ." >". $x['button2']['DOM'] ."</a></span>";
      }
      // Button 3
      if($x['button3']){ 
        $Items[$y] .= "<span title=". $x['button3']['toolTip'] ." class='w3-bar-item w3-buttonw3-xlarge w3-right'><a href=". $x['button3']['link'] ." >". $x['button3']['DOM'] ."</a></span>";
      }
      // Avatar
      if(!$x['picture']){ $x['picture'] = $this->PicGenerator($this->Array[$c],$noPic); }
      if($x['picture']){ $Items[$y] .= "<img src='Media/$picPath/". $x['picture'] ."' class='w3-bar-item w3-circle w3-hide-small' style='width:85px'>"; }
      //List Content
      $Items[$y] .= "<div class='w3-bar-item'>";
      //Main + click link
      $Items[$y] .= $x['onClick']?  "<a class='w3-large' href=". $x['onClick'] ." >". $x['main'] ."<br></a>": "<div class='w3-large'>" . $x['main'] . "</div>";
      //Sub, hidden and closure
      $Items[$y] .= "<span>". $x['sub'] ."</span><span hidden>". $x['hidden'] ."</span>
                    </div>
                    </li>";
      $c++;
    }
    $Items['__post'] = "</ul> </div>";

    return $Items;
  }

   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //Make striped colored list
  // -------------------------------------------------------------
  // | [main]                               [sub]                 |
  // -------------------------------------------------------------
  // $array = The array to be drawned, if empty, use class's Array PROPERTY instead
  // $Class = ARRAY of class option 'color', 'hover'
  public function Zebra($array=null,$Class=array('color'=>'white','hover'=>'lime')){
    $ulId = $this->ulId;
    $array= $array? $array: $this->RefinedArray;
   

    //Opening
    $Items['__pre'] = "<div class=''>
                    <ul class='w3-ul w3-card-4 stripped_li w3-" . $Class['color'] . "' id=$ulId>";
    
    //Items
    $c=0;
    foreach($array as $y=>$x){
      $x['main'] = $this->Stringify($x,'main');
      $x['sub'] = $this->Stringify($x,'sub');

      $Items[$y] = "<li id='" . $ulId . "_li_" .$y. "' class='w3-hover-" . $Class['hover'] . "' >";
      //Main + click link
      $Items[$y] .= "<div class='w3-cell w3-cell-middle' style='width:60%'>";
      $Items[$y] .= $x['onClick']?  " <span ><a class='w3-large' href=". $x['onClick'] ." >". $x['main'] ."</a></span>": "<span class='w3-large'>" . $x['main'] . "</span>";
      $Items[$y] .= "</div>";
      //Sub, hidden and closure
      $Items[$y] .= "<div class='w3-cell w3-cell-middle' style='width:40%'><span >". $x['sub'] ."</span></div><span hidden>". $x['hidden'] ."</span>
                    </li>";
      $c++;
    }
    $Items['__post'] = "</ul></div>";

    return $Items;
  }



  ///////////////////////////////////////////////////////////////////////////////////////////////
  // CALLBACK : STR of picture path
  // Generate picture using supplied data
  // $Data = The ARRAY of current data to be generated pic
  // $Method = Sex : generate pic using 'sex' key
  function PicGenerator($Data,$Method){
    switch ($Method){
      case 'Sex':
        switch ($Data['sex']){
          case '$lanMale':
          $pic='def_male.png';
          break;
          case '$lanFemale':
          $pic='def_female.png';
          break;
          case '$lanAlien':
          $pic='def_alien.png';
          break;
          case '$lanUnidentified':
          $pic='def_unknown.png';
          break;
        }
        break;
    }
    return $pic;
  }

  ///////////////////////////////////////////////////////
  //
  // Stringify the Array in 'main'/'sub' with delimiter
  // $x = Each of the RefinedArray property [ foreach($this->RefinedArray as $x) ]
  // $which = Which one? 'main' or 'sub'
  private function Stringify($x,$which){
    foreach($x[$which]['val'] as $i=>$v){
      $l .= $l? $x[$which]['del'] . $v :$v ;
    }
    return $l;
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Make Search function
  function FilterList(){
    $ulId = $this->ulId;      
      $Form= "<div class='w3-card w3-white w3-panel'>
                  <div class='w3-row w3-panel'>
                      <label for='listFilter' class='w3-text-green'>". $GLOBALS['lanFilter'] ."</label>
                      <input name='listFilter' class='w3-input  w3-border' id='listFilter' type=search onkeyup=\"listFilterHide('$ulId','listFilter')\">
                  </div>
              </div>";
      return $Form;
    }

  ///////////////////////////////////////////////////////////////////
  // Drawer
  public function Draw($List){
    echo "<div class='w3-card-4 w3-container w3-light-green w3-center'><h3 class='w3-panel'>" . $this->Arguments['heading'] . "</h3></div>";
    if($this->Arguments['filter']=='top'){ echo $this->FilterList(); }
    Gardevoir($List);
    if($this->Arguments['filter']=='bottom'){ echo $this->FilterList(); }
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
  public $Additional; // additional $_POST[x] that contain datalist but didn't exist in 'Layout' table

  function __Construct($Data,$Additional=null,$Layout=null,$List=null){
    //(Data: array(field_id:value) || Additional: additional $_POST[x] that contain datalist but didn't exist in 'Layout' table) || Lauout&List: GoodBoi object of 'layout'&'list' db
    if (empty($Layout)) { $Layout= new GoodBoi('layout'); }
    if (empty($List)) { $List= new GoodBoi('list_list'); }
    $this->Layout = $Layout;
    $this->List = $List;
    $this->Data=$Data;
    $this->Additional = $Additional;
    mark($this->Data, "DATA ");
    $this->InsertList();
  }

  function CekIfNew($ListID,$Cluster){
    $List=$this->List->GoCount("WHERE cluster='". $Cluster ."' AND list_value='". $ListID ."'");
    if($List == 0 ){ mark("NEW LIST"); return TRUE; } else { mark("OLD LIST"); return FALSE; }
  }

  function InsertList(){
    $Layout=$this->Layout->GoFetch("WHERE field_type='datalist' && field_list_table IS NULL");
    if($this->Additional){ $Layout =array_merge($Layout,$this->Additional); }
    $Lay=array(); //Lay is array (field_id => field_list) of those field type datalist
    //Make $Lay
    foreach($Layout as $x){
      $Lay += array($x['field_id']=>$x['field_list']);
    }

    foreach($this->Data as $y=>$x){
      if(array_key_exists($y,$Lay)){
        $x= UnJson($x);
        $x= is_array($x)? $x : array($x);
        foreach($x as $xx){
          if($this->CekIfNew($xx,$Lay[$y]) === TRUE && !empty($xx)){
            $Kue=$this->MakeQuery($xx,$Lay[$y]);
            DeFlea($Kue, "Kue ");
            $this->List->GoBark($Kue);
          }
        }
      }
    }
  }

  function MakeQuery($Data,$Cluster){
    $Ord=$this->List->GoCount("WHERE cluster='". $Cluster ."'");
    $Que=array('cluster'=>$Cluster,'list_name'=>$Data,'list_value'=>$Data,'active'=>1,'list_order'=>$Ord+1,'creator'=>$_SESSION['Person']);
    $BARK= new Snorlax ('id','com_list_list',$Que,null,'New',$this->List);
    return $Que;

  }
}

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
////////////////////////////////////////////////////////////////////



Class for drawing form utilizing the 'layout' table.
To use: Declare the object, then use object->start() to return array contain the drawed list, which can be drawn using 'Gardevoir' function [ Gardevoir(object) ]

Arguments:

($FormID,$Job,$Arguments)

>> $FormID = The form's id (column 'form_id' in Layout table)
>> $Job = what job currenty is ('reg','view','edit')
===========Arguments ARRAY=================
>> Reg = [ARRAY] class for the 'reg' job (element:class) : see element list [Default=Setting]
>> View = [ARRAY] class for the 'view' job (element:class) : see element list [Default=Setting]
>> Edit = [ARRAY] class for the 'edit' job (element:class) : see element list [Default=Setting]
>>FHeader = Form Header
>>DataID = Which row to show on 'view' and 'edit' [Default=$_GET['dataid'] ]
>>Button = Array of Button
>>DataTable = MySQL Table to be used for data in edit/view mode
>>DataKey = The column thet contain key column of DataTable
>>DataID = The Row ID of the DataTable (Default is supplied from $_GET['dataid'])
>>Hide = [TRUE/FALSE] Hide the form (nested by groups, can be shown by clicking each group's header) except for the first group? (Default is TRUE)
>> Auto = Auto Run the process on construct [Default=True]
===========Arguments=================
>>$SuppliedData = ARRAY (field_id:value) to be assigned in edit/view if no table and parameters supplied

Element List:
-FormDiv
-FormHeader
-Form
-GroupDiv
-GroupHeader
-GroupContainerDiv
-InputDiv
-Input
-Select
-datalist
-InputLabel
-Tooltip
-SideLabel
-SubmitButton
-ResetButton
-EditButton

Flow
1. Prepare the DB and Draw the main form pre and post
2. Draw group
3. Draw each field
    -Set attribute
    -Set label
    -Draw the field
*/


class Smeargle{
  protected $FormID,$Job,$ContentTable,$ContentTableID,$Class,$FHeader,$Layout,$Data,$SuppliedData,$FormHide;

  function __Construct($FormID,$Job,$Arguments=null,$SuppliedData=null){
    $CReg = Empty($Arguments['Reg'])? $GLOBALS['SettingDefaultSmeargleRegClass'] : $Arguments['Reg'] ;
    $CView = Empty($Arguments['View'])? $GLOBALS['SettingDefaultSmeargleViewClass'] : $Arguments['View'] ;
    $CEdit = Empty($Arguments['Edit'])? $GLOBALS['SettingDefaultSmeargleEditClass'] : $Arguments['Edit'] ;
    $Auto = Empty($Arguments['Auto'])? True : $Arguments['Auto'] ;
    $this->FormHide = $Arguments['Hide']? $Arguments['Hide'] : TRUE ; 

    $this->DataID = Empty($Arguments['DataID'])? $_GET['dataid'] : $Arguments['DataID'] ;

    $this->FormID = $FormID;
    $this->Job = $Job;
    $this->FHeader = $Arguments['FHeader'];
    $this->ContentTable = $ContentTable;
    $this->ContentTableID = $ContentTableID;
  
    // Fetching the content data on edit and view job
    switch ($Job){
      case 'edit':
      case 'view':
      // If Arguments DataTable, DataKey, DataID given, use that to fetch data, otherwise, use $SuppliedData
      if (empty($Arguments['DataTable'] . $Arguments['DataKey'])){
        $this->SuppliedData=$SuppliedData;
      } else {
        mark($Arguments['DataTable'],"DataBtABe");
        mark($Arguments['DataKey'] . " = " . $DataRow ,"DataBtABasae");
        $DataSup = new GoodBoi($Arguments['DataTable']);
        $DataRow= $Arguments['DataID']? $Arguments['DataID'] : $_GET['dataid']; // If Arguments['DataID'] is empty, use default instead (which is $_GET['dataid'])
        $this->SuppliedData =  $DataSup->GoFetch("WHERE ". $Arguments['DataKey'] ."='". $DataRow ."'");
      }
     
        break;
    }
    
   

    // Decided class based on Job
    switch ($Job){
      case ('reg'):
        $this->Class=$CReg;
        break;
      case ('view'):
        $this->Class= empty($CView)? $CReg : $CView ;
        break;
      case ('edit'):
        $this->Class= $CEdit? $CEdit : $CReg ;
        break;
    }

  


    // Auto Run if $Auto is true
    // if ($Auto){ $Draw = $this->Start(); }
  }  


  //Start the draw
  function Start(){
    $this->Layout = new GoodBoi("layout");
    $this->Data = new GoodBoi($this->ContentTable);
    $Group=$this->Layout->GoFetch("WHERE form_id='". $this->FormID ."' ORDER BY group_order","DISTINCT group_cap, group_order");

      // Pre
      $URI=GetGET();
    $Draw['Pre'] = "<div  class='". $this->Class['FormDiv'] ."'>
                    <h3 class='". $this->Class['FormHeader'] ."'>". $this->FHeader ."</h3>
                    <form id='". $this->FormID ."' class='". $this->Class['Form'] ."' action=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?$URI method='POST' enctype=multipart/form-data >";

      // Grouping
    foreach($Group as $x){
      $Draw['Group_'.$x['group_cap']] = $this-> Grouping($x['group_cap']);
    }

    //Post
    $Mod =$_GET['mod']; // Get current mod for edit button
    $Button['Submit']="<input type=submit class='w3-button w3-blue ' value=". $GLOBALS['lanSubmit'] .">";
    $Button['Reset']="<button type=reset class='w3-button w3-blue '>". $GLOBALS['lanReset'] ."</button>";
    $Button['Edit']="<a  href='". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=$Mod&job=3&dataid=".$this->DataID."' link class='w3-button w3-blue '>". $GLOBALS['lanEdit'] ."</a>";
      //Button
        $ButtonSet="<div class='w3-row'>";
        switch($this->Job){
          case 'reg':
          case 'edit':
            $ButtonSet.= "<div class='w3-col m6 w3-center'>". $Button['Submit'] ."</div>" ;
            $ButtonSet.= "<div class='w3-col m6 w3-center'>". $Button['Reset'] ."</div>" ;
            break;
          case 'view':
            $ButtonSet.= "<div class='w3-col m12 w3-center'>". $Button['Edit'] ."</div>" ;
            break;
        }
        $ButtonSet.="</div>";

    $Draw['Post'] = "$ButtonSet</form></div>";
    
    return $Draw;
  }
  
  //Decided what method to drew the fields
  function Grouping($Group){
    switch ($this->Job){
      case 'reg':
      case 'edit':
        return $this->RegEdit($Group);
        break;
      case 'view':
      return $this->View($Group);
        break;
    }

  }

  //Draw the field
  function RegEdit($Group){
    //Fetch Fields from each group
    $Fields = $this->Layout->GoFetch("WHERE form_id='". $this->FormID ."' AND group_cap='$Group' ORDER BY field_order");

    //Draw Group Header

    if ($this->FormHide && $this->HideGroup) { $w3hide='w3-hide w3-animate-slide'; }// Hide field groups excetp for the first one (showabel by clickin the header)
    $this->HideGroup = TRUE;

    $Draw['Header']= "<div class='". $this->Class['GroupDiv'] ."'><h4 class='". $this->Class['GroupHeader'] ."' onclick=BukaTutup('group_". str_replace('$','',$Group) ."')>". lan2var($Group) ."</h4><div id='group_" . str_replace('$','',$Group) ."' class='". $this->Class['GroupContainerDiv'] ." $w3hide'>";

    //Process each Fields
    foreach($Fields as $x){

      //Check for available display options ($DpOpt)
      $DpOpt=UnJson($x['field_options']);
      if($DpOpt['DivClass']){ $DivClass=$DpOpt['DivClass']; unset($FieldDivClosue); }
      /*
        // w3row : Make a div w3-row class that can be used to clumps together several field into single line.
        //It work by changing $Display to 'w3row', therefore, in drawing process in latter part of this function, it will skip the usual <div> and </div> drawing, instead, it will draw <div class='w3-row'> on field with w3row=start and draw div closur at w3row=end, also, it make $DisplaySpecificClass into 'w3-col 3' which will be inserted at the end of the field
        if($DpOpt['w3row']){ 
          switch ($DpOpt['w3row']){
            // Check if it is first field of the groups(w3row=start), if yes, make a div with w3-row class
            case 'start': 
              $Draw[$x['field_id']] = "<div class='w3-row'>";
              break;
              // Check if it is last field of the groups(w3row=end), if yes, make a div closure
            case 'end':
              $FieldDivClosue= "</div>";
              break;
          }
            $Display = 'w3row';
            $DisplaySpecificClass='w3-col 3';
        } else { unset($Display); }
        */
        


      $Visible=UnJson($x['field_permission']);
      
      if($Visible[$this->Job]<1){ continue; } // If supposed to be not vissible, stop and skip to the next field

      //Assign attributs
      foreach(UnJson($x['field_attrib']) as $b=>$a){
        $Attribs= " $b='$a' ";
      }

      //Assign value
      $FieldValue= $this->SuppliedData[0][$x['field_id']];

      //Tooltips
      $tooltip = $x['field_tooltip']? "<span class='". $this->Class['Tooltip'] ."' id=tip_". $x['field_id'] .">". lan2var($x['field_tooltip']) ."</span><script>
      $('#".  $x['field_id'] ."').focus(function(){
        $('#tip_". $x['field_id'] ."').css('visibility', 'visible')
      });
      $('#".  $x['field_id'] ."').blur(function(){
        $('#tip_". $x['field_id'] ."').css('visibility', 'hidden')
      });
    </script>  " : "" ;

      //Input type
      switch($x['field_type']){
        case 'select':
          $Input="select";
          $Additional = DrawOption($x,array('default'=>$this->SuppliedData[0][$x['field_id']]));  
          $Additional .="</select>";
          $InputSpecificClass = $this->Class['input_select'];
          break;
        case "password":
          $Input="input type=password ";
          if($this->Job=="edit"){
            $Pre= "<br>". $GLOBALS['lanOldPass']. "<input type=password id=oldpsw name=Exc-". $x['field_id'] . "Old></input>" ;
          }
          $Additional="<label for=psw2>". $GLOBALS['lanConfirmPass'] ."</label> : <input id=psw2 type=password require name=Exc-".  $x['field_id'] ."Conf>
          <div id=GEMRPidgey hidden>". $GLOBALS['lanPsMissmatched'] ."</div><script>$('#psw2').keyup(ConfirmPassword)</script>";
          break;
        case 'textarea':
          $Input="textarea ";
          $Additional="</textarea>";
          break;
        case 'auto':
          $autoval = AutoField($x['field_id']);
          $Input = "input type='hidden' value='". $autoval['val'] ."'";
          $InputSpecificClass = $this->Class['input_'. $x['field_type']];
          $Additional = "<span id='auto_". $x['field_id'] ."' class='". $this->Class['Input'] ." $InputSpecificClass ". $x['field_class'] ."'>". $autoval['text'] ."</span>";
          break;
        default:
          $Input = "input type='". $x['field_type'] ."'";
          $InputSpecificClass = $this->Class['input_'. $x['field_type']];
          if($x['field_type']=='datalist'){
            $Input .=" list='list_". $x['field_id'] ."'";
            $Additional = "<datalist id='list_". $x['field_id'] ."'>";
            $Additional .= DrawOption($x);  
            $Additional .="</datalist>";
            
          }
          if($this->Job=='edit'){
            $Attribs .= " value='". lan2var($FieldValue) ."'"; // Add value to attribs if in edit mode
           }
          break;
      }

      //Main drawer


          $Draw[$x['field_id']] .= "<div class='". $this->Class['inputdiv'] ."  $DivClass'>";
          
          
          if(!empty($x['field_label'])){ 
          $Draw[$x['field_id']] .= "<label for='". $x['field_id'] ."' class='". $this->Class['InputLabel'] ."'>". lan2var($x['field_label']) ."</label>"; // Label
          }


          $Draw[$x['field_id']].= "<$Input name='". $x['field_id'] ."' id='". $x['field_id'] ."' class='". $this->Class['Input'] ." $InputSpecificClass ". $x['field_class'] ."' $Attribs >$Additional"; // Draw input and additional

          $Draw[$x['field_id']].= $tooltip; //Tooltip

          $Draw[$x['field_id']] .="<span id='side_". $x['field_id'] ."' class='". $this->Class['SideLabel'] ."'>". lan2var($x['field_side']) ."</span>"; // Side Label

          $Draw[$x['field_id']].= "</div>";

    


          
          
      //Unset Var
      unset($Additional);
      unset($Attribs);
      unset($InputSpecificClass);
      unset($DivClass);
    }

    

    //Draw Group Footer
    $Draw['Footer']= "</div></div>";
    if($Fields){ return $Draw; } //return only if there is field visible in a group, otherwise, dont'return (empty)
    
  }


  function View($Group){
    

        //Fetch Fields from each group
        $Fields = $this->Layout->GoFetch("WHERE form_id='". $this->FormID ."' AND group_cap='$Group' ORDER BY field_order");
        $Fields = PhoenixDown($Fields);
        //Draw Group Header
        if ($this->FormHide && $this->HideGroup) { $w3hide='w3-hide w3-animate-slide'; }// Hide field groups excetp for the first one (showabel by clickin the header)
          $this->HideGroup = TRUE;

          $Draw['Header']= "<div class='". $this->Class['GroupDiv'] ."'><h4 class='". $this->Class['GroupHeader'] ."' onclick=BukaTutup('group_". str_replace('$','',$Group) ."')>". lan2var($Group) ."</h4><div id='group_" . str_replace('$','',$Group) ."' class='". $this->Class['GroupContainerDiv'] ." $w3hide'>";

        //Process each Fields
        foreach($Fields as $x){

          //Visibility
          $Visible=UnJson($x['field_permission']);
          if($Visible[$this->Job]<1){ continue; } // If supposed to be not vissible, stop and skip to the next field

          //Image
          if($x['field_options']['picture']){
            mark($x['field_options'],"PIC");
            $this->SuppliedData[0][$x['field_id']] = "<img src=\"Media/". $x['field_options']['picture'] ."/". $this->SuppliedData[0][$x['field_id']] ."\" >";
          }


          //Main drawer
          $Draw[$x['field_id']] = "<div class='". $this->Class['Datadiv'] ."'>";

          if(!empty($x['field_label'])){ 
            $Draw[$x['field_id']] .= "<span class='". $this->Class['DataLabel'] ."'>". lan2var($x['field_label']) ."</span> : "; // Label
          }

          $Draw[$x['field_id']].= "<span id='". $x['field_id'] ."' class='". $this->Class['Data'] ." $InputSpecificClass ". $x['field_class'] ."'>". lan2var($this->SuppliedData[0][$x['field_id']]) ."</span>"; // Draw input and additional

          $Draw[$x['field_id']].= "</div>";

          //Unset Var
          unset($InputSpecificClass);
        }

        
    //Draw Group Footer
    $Draw['Footer']= "</div></div>";
    if($Fields){ return $Draw; } //return only if there is field visible in a group, otherwise, dont'return (empty)
    }
  
}

/*

================== OLD SMEARGLE========================

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
    $this->Data= empty($_GET['dataid'])? $Data: $_GET['dataid']; //If No Specific user, get own instead
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
    $Draw['pre'] = "<form action=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?$URI method=$method enctype=multipart/form-data id=". $this->Layout . ">"; 
    
    //---Draw form
   $Draw['grouping']= $this->Grouping(); //  Go to function Grouping to draw each group
   
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
      $Draw['post']= "<br/><input class='w3-button w3-red' type=Submit value=$Button[0]></input> <input class='w3-button w3-red' type=reset value=$Button[1]></input>";
      break;
    case "edit":
    $Draw['post']= "<br/>
                        <input type=hidden name=". $this->DataID ." value=". $this->UserData[$this->DataID] .">
                        <input type=Submit value=$Button[2]></input> <input type=reset value=$Button[1]></input>"; 
      break;
    case "view":
    $Draw['post']= "<br/><a href='". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_login&job=4' class='w3-button w3-red'>$lanEdit</a>";
      break;
   }

   //Draw closur </table> and </form>"
   $Draw['post'] .= "</form>";
   return $Draw;
  }

  function Grouping($style="layout_group"){
    // Fetching DB from layout table
    $Group=$this->MySQL_Object-> GoFetch("WHERE form_id = '". $this->Layout ."' ORDER BY group_order",'DISTINCT group_order, group_cap');
    


    //==================Drawing GROUPING============
    foreach($Group as $g){
      $Draw[$g['group_cap'] .'_pre'] = "<br/><div class='w3-card w3-round w3-white' >
      <h5 class='w3-button w3-block w3-left-align w3-lime' onclick=BukaTutup('". $g['group_cap'] ."')>". lan2var($g['group_cap']) ."</h5>
      <fieldset class='w3-container w3-padding w3-margin' id=". $g['group_cap'] ." class=$style>";
        $Draw[$g['group_cap'] .'_fielding'] =  $this->Fielding($g['group_cap']); //Go to Fielding function to draw field
      $Draw[$g['group_cap'] .'_post'] = "</fieldset></div>";
    }
    return $Draw;
  }

  //==================Drawing FIELDING TRIAGE============
  function Fielding($Group){
    $View="field_visible_". $this->ViewMode;
    $Field=$this->MySQL_Object-> GoFetch("WHERE form_id = '". $this->Layout ."' AND group_cap='". $Group ."' AND ($View <> 0 $admin) ORDER BY field_order"); // Fetching from MySQL, the field with the same Group ID which is visible

    switch($this->ViewMode){
      case "edit":
        $UserData=$this->MySQL_Data-> GoFetch("WHERE ". $this->DataID ." = '". $this->Data ."'" );
        $this->UserData=$UserData[0];
      case "new":
        $Draw=$this->NewFielding($Field,$Group);
        break;
      case "view":
        $Draw=$this->ViewFielding($Field,$Group);
        break;
    }
    return $Draw;
  }

  //==================Drawing NEW FIELDING============
  // Draw field if viewing mode is Edit or New
  function NewFielding($Field,$Group){
    
    //Make connection to list_list/custom list table for use latter
    $List=new GoodBoi("list_list");
    //custom list
    $Custom_List_Class=$this->MySQL_Object-> GoFetch("WHERE form_id = '". $this->Layout ."' AND (field_list_table IS NOT NULL OR field_list_table <> 0)",'DISTINCT field_list_table');
    $this->CustomList=array();
    foreach($Custom_List_Class as $x){
      if(!empty($x['field_list_table'])){
        array_push($this->CustomList,$x['field_list_table']);
      }
    }


    foreach($Field as $F){ // Script for each found filed
      
      // Assign attributes
      $Hidden= strpos($F['field_id'], 'hidden_') !== false? "hidden" : "";
      $F['field_id']=str_replace("hidden_","",$F['field_id']);
      $minlength= empty($F['field_minlength']) ? "" : "minlength=". $F['field_minlength'] ;
			$placeholder= empty($F['field_placeholder'] ) ? "" : "placeholder='". lan2var($F['field_placeholder'])  ."'";
      $validator= empty($F['field_validation'] ) ? "" : "pattern='". $F['field_validation'] ."'" ;
      $required= empty($F['required'] ) ? "" : "required" ;
      $GLOBALS['SideHelper'] = empty($F['field_side'] ) ? "" : lan2var($F['field_side'])  ;
      $Width= empty($F['field_col'])? "18" : $F['field_col'];
      $Height= empty($F['field_row'])? "1" : $F['field_row'];
      $Label= empty($F['field_label'])? " " : "<label class=w3-input for=". $F['field_id'] .">". lan2var($F['field_label']) ."</label>";
      

      $Tooltip= empty($F['field_tooltip'])? "" : "<span class=tooltiptext id=tip_". $F['field_id'] .">". lan2var($F['field_tooltip']) ."</span>
      <script>
        $('#".  $F['field_id'] ."').focus(function(){
          $('#tip_". $F['field_id'] ."').css('visibility', 'visible')
        });
        $('#".  $F['field_id'] ."').blur(function(){
          $('#tip_". $F['field_id'] ."').css('visibility', 'hidden')
        });
      </script>  ";

      $LineBreak= empty($F['field_seperator'])? "<br/>" : $F['field_seperator'];

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
          $Input="select class='w3-select w3-border'";
          $Close="select";
          $InputContent = $Options;
          break;
        case "datalist":
          $Input="input size=$Width $d list=". $F['field_list'];
          $Close="input";
          $InputContent = "<datalist id=". $F['field_list'] .">". $Options ."</datalist>";
          break;
        case "auto":
          $Input="input value='". AutoField($F['field_id']) ."' type=hidden $placeholder $minlength ";
          $Close="input";
          break;
        case "password":
          $Input="input size=$Width type=password ";
          $Close="input";
          if($this->ViewMode=="edit"){
            $Pre= "<br>". $GLOBALS['lanOldPass']. "<input type=password id=oldpsw name=Exc-". $F['field_id'] . "Old></input>" ;
          }
          $Additional="<br><label for=psw2>". $GLOBALS['lanConfirmPass'] ."</label> : <input id=psw2 type=password require name=Exc-".  $F['field_id'] ."Conf></input>
          <div id=GEMRPidgey hidden>". $GLOBALS['lanPsMissmatched'] ."</div><script>$('#psw2').keyup(ConfirmPassword)</script>";
          break;
        case 'textarea':
          $Input="textarea $defaultvalue $placeholder $minlength ";
          $Close="textarea";
          break;
        default:
          $Input="input type=". $F['field_type'] ." size=$Width $defaultvalue $placeholder $minlength";
          $Close="input";
          break;
      }

      //If ciled label contain $lan, get that into variable
      $F['field_label'] = lan2var($F['field_label']);
     

      //The main drawing script
      if(empty($Draw)){ $Draw[$F['field_id']]= "<legend class='w3-panel w3-green'>". lan2var($Group) ."</legend>"; } //Group Header (Drawed only when at least one element showed, and only once in each group)
      
      $Draw[$F['field_id']] .= "$LineBreak $Pre
                          $Label
                          <$Input  name=".  $F['field_id'] ." id=".  $F['field_id'] ."$required $Hidden>
                            $InputContent
                          </$Close> 
                          <span id=side_". $F['field_id'] .">".  $GLOBALS['SideHelper'] ."</span> $Tooltip  
                      $Additional
                      "; // Input
                      unset($InputContent);
                      unset($Additional);
                      unset($Pre);
                      unset($GLOBALS['SideHelper']);
                    }
                    
                    return $Draw;
                    
  }

    //==================Drawing VIEW FIELDING============
  // Draw field if viewing mode is View
  function ViewFielding($Field,$Group){
    $Staff=$this->MySQL_Data->GoFetch("WHERE ". $this->DataID ." = '". $this->Data ."'");
    foreach($Field as $F){ // Script for each found filed
      
      //Check if content is an Array
        $FLabel=deserialization(lan2var($Staff[0][$F['field_id']]),1,array(":"));
      

      if(empty($Draw)){ $Draw[$F['field_id']]= ""; } //Group Header (Drawed only when at least one element showed, and only once in each group)
      $Draw[$F['field_id']] .= "
                        
                          ". lan2var($F['field_label']) ."
                        
                          ". $FLabel ."
                        "; // Input
    }
    return $Draw;
  }
}

*/


//////////////////////////////////////////////////////////////////////////
///////////////   AUTO                                     ///////////////
/////////////////////////////////////////////////////////////////////////

function AutoField($Type,$Argument=null){
  switch($Type){
    case 'patient':
     $Patient= new GoodBoi('com_gita_patient');
     $PX = $Patient->GoFetch("WHERE patientid='". $_SESSION['Patient'] ."'","patientid,prefix,FName,LName");
     DeFlea($PX[0]['prefix'] ." ". $PX[0]['FName'] ." ". $PX[0]['LName']);
      return array('val'=>$PX[0]['patientid'],'text'=>FullName($PX[0]));
      break;
    case 'age':
      $Patient= new GoodBoi('com_gita_patient');
      $PX = $Patient->GoFetch("WHERE patientid='". $_SESSION['Patient'] ."'","dob");
      $BD = $PX[0]['dob'];
      return array('val'=>getAge($BD),'text'=>AgeText($BD));
    case 'provider':
      $Prov= new GoodBoi('staff_list');
      $Dr = $Prov->GoFetch("WHERE usrid='". $_SESSION['Person'] ."'","usrid,prefix,FName,MName,LName");
      return array('val'=>$Dr[0]['usrid'],'text'=>FullName($Dr[0]));;
    case 'stat_BMI':
    break;
  }
}

?>
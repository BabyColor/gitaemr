<?
//================================================================================================
//                            LOGIN ACTION
//================================================================================================

if(!empty($_SESSION['Person'])){
    mark("Already Loged In Script PlaceHolder");
   } else {
    //-----Check Login Method
    $_POST = array_map('strip_tags', $_POST); //STRIPPING
    $LoginMethod=explode(",",$LoginMethod);
    $Dalmantion= new GoodBoi("staff_list");


    if(!empty($_POST)){
    //-------------Check registereed user
    foreach($LoginMethod as $x){
            //Break checking loop if user already found
            $dog=$Dalmantion->GoFetch("WHERE ". $x ."='". $_POST['UserName'] ."'");
            $hash=$dog[0]['Password'] ;
            if(!empty($hash)){ break; }
            $ErrorLog=$ErrorLog ."No ". $x ." Found, ";
        }

        if(empty($hash)){ 
            $LoginError=$lanLoginNoUser; 
            $LogDes="Login Failed: No User Found";
            $ErrorLog=$ErrorLog ."for ". $_POST['UserName'];
        } // If no user found, set Error Message
        if(!empty($LogRawPass)){ //hashing password for verivication if RawPass is set
            $_POST=array_replace($_POST,array("Password"=>password_hash($_POST['Password'], PASSWORD_DEFAULT)));
        }
        if (empty($LoginError) && !password_verify($_POST['Password'], $hash)){ 
            $LogDes="Login Failed: Password did not match";
            $ErrorLog="Wrong Password for ". $_POST['Password'];
            if($LoginErrorMessage!="Each"){
                $LoginError=$lanLoginWrongPass;
                
            } else {
                $LoginError=$LoginError ."<br>". $lanLoginWrongPass;
            }
        }
        mark($dog[0]['usrid'],"USER ID");
        if(empty($LoginError)){
            //Login Succesfull
            OKDialog($lanLoginSuccessT,$lanLoginSuccessC);
            Login($dog[0]['usrid']);
            mark($_SESSION,"Logged in as ");
            $LogDes="Login";
        } else {
            ErrorDialog($lanLoginFailedT,$LoginError);
            
        }
        mark($LogDes,"LOG <br>");
        mark($ErrorLog);
    }
    //================================================================================================
    //                            LOGIN FORM
    //================================================================================================
    if((empty($_POST) || !empty($LoginError)) && empty($_SESSION['Person'])){ // Only draw login form if data hasn't submited, or Login Error occured
        include "login.html";
    }
        if(empty($LogRawPass)){ // Hashing password if Raw Password option is set to 0
            $_POST=array_replace($_POST,array("Password"=>password_hash($_POST['Password'], 		PASSWORD_DEFAULT)));
        }
    $LogContent=array2arrow($_POST); //For Logging

    //================================================================================================
    //                            ALREADY LOGGED IN
    //================================================================================================

}
?>
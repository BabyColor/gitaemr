<?php
require "Engine/head.php";

$StaffListTab = new GoodBoi('staff_list');
$StaffList = $StaffListTab -> GoFetch("LIMIT 8","Aang, UserName, prefix, FName, MName, LName");
$StaffLength = count($StaffList);

//================================================================================================
//                            LOGIN ACTION
//================================================================================================

if(!empty($_SESSION['Person'])){
       } else {
        //-----Check Login Method
        $_POST = array_map('strip_tags', $_POST); //STRIPPING
        $LoginMethod=explode(",",$LoginMethod);
        $StaffListTab= new GoodBoi("staff_list");
    
    
        if(!empty($_POST)){
        //-------------Check registereed user
        foreach($LoginMethod as $x){
                //Break checking loop if user already found
                $dog=$StaffListTab->GoFetch("WHERE ". $x ."='". $_POST['UserName'] ."'");
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
            if(empty($LoginError)){
                //Login Succesfull
                //OKDialog($lanLoginSuccessT,$lanLoginSuccessC);
                Login($dog[0]['usrid']);
                echo "
                        <div class='w3-card-4 w3-green w3-panel w3-center' style='margin: 20%'>
                        <h2>$lanLoginSuccessT</h2>
                        <div class='we-large'>$lanLoginSuccessC</div>
                        <div class='we-large'><a href=index.php><i class='material-icons'>home</i></a></div>
                        </div>
                        ";
                $LogDes="Login";
                die();
            } else {
                ErrorDialog($lanLoginFailedT,$LoginError);
                
            }
            mark($LogDes,"LOG <br>");
            mark($ErrorLog);
        }
}

?>

<html>

<head>
    <?php  ?>
    <script>
        var currentCharacter;
        var characterCursor = 0;
        
        

        function CharSelect(userName) {
            userName = userName.replace('char_', '');
            if (currentCharacter == userName) { return; }
            $(loginForm).removeClass('w3-hide');
            var BGChar;

            $.ajax({
                url:'Media/Potrait/' + userName + '.png',
                type:'HEAD',
                error: function()
                {
                        BGChar = 'Media/Potrait/unknown'+Math.floor((Math.random() * 5) + 1)+'.png';
                        console.log("AJAX E");
                        console.log(BGChar);
                },
                success: function()
                {
                        BGChar = 'Media/Potrait/' + userName + '.png';
                        console.log("AJAX S");
                        console.log(BGChar);
                }
                });
            
            $("#bgCharacter")
                .stop()
                .animate({
                    left: '-300px',
                    opacity: '0'
                }, 300)
                .queue(function () {
                    if(!BGChar){ BGChar='Media/Potrait/' + userName + '.png' }
                    $("#bgCharacter img").attr('src', BGChar);
                    $(this).dequeue();
                })
                .animate({
                    left: '0',
                    opacity: '1'
                }, 600);


            $("#StaffName")
                .stop()
                .animate({ opacity: '0' })
                .queue(function () {
                    $(this).text($("#char_" + userName).attr('name')).dequeue();
                })
                .animate({ opacity: '1' });
            currentCharacter = userName;
            $("#username").val(userName);

        }
    </script>
</head>

<body class='w3-grey'>
    <div class=' w3-center' style='margin: auto'>
        <h3 class='w3-center w3-card-4 w3-white w3-animate-fading' style='width:80%;max-width:1024px; margin: auto'>
            <?php echo $lanLoginTitle; ?>
        </h3>

        <div class='w3-display-container w3-card-4 w3-black' style='height:80%; width:80%; max-width:1024px; overflow: hidden; margin: auto '>
            <div id='bgCharacter' class='w3-display-container w3-animate-left' style=' float: left; max-height:100%; overflow: hidden; animation: animateleft 1s'>
                
               <!-- <div class='w3-display-bottomleft w3-row'  style='height:100%; width:101%'>
                    <div class='w3-col m6' style='height:100%;'>a</div>
                    <div class='character_bg_gra_right w3-col m6' style='height:100%;'>a</div>
                </div>
                <div class='character_bg_gra_top w3-display-topmiddle' style='height:10%; width:100%'></div>
                <div class='character_bg_gra_bottom w3-display-bottommiddle' style='height:20%; width:100%'></div>-->
                <div style=' height:100% ; width:100%' class='w3-left-align'>
                    <img src='' style='height:100%; overflow: hidden'>
                </div>

            </div>
            <div id='characterInfo' class='w3-display-topright w3-right'>
                <div id='StaffName' class='w3-panel w3-bottombar w3-border-white w3-right w3-xxlarge'>
                    
                </div>
            </div>
            <div class='w3-display-bottommiddle' style='width:70%; max-width:700px'>
                <div id='characterSelection' class='w3-panel w3-center w3-row'>
                        <?php 
                                foreach($StaffList as $t=>$x){
                                        if(file_exists('Media/Korra/'. $x['Aang']) && $x['Aang'] ){
                                                $Ava = $x['Aang'];
                                        }  else { $Ava = 'unlock-character.png';}
                                        echo "<div class='w3-col m3'>
                                                <img id='char_". $x['UserName'] ."' class='green-padding-supersmall w3-sepia  w3-animate-zoom characterPhoto' src='Media/Korra/$Ava'                                     style='width:100%; animation: animatezoom 0.2s' name='". FullName($x) ."'>
                                                </div>";
                                }

                                echo "<span id='StaffCount' hidden>$StaffLength</span>"
                        ?>
                  

                </div>
                <form id='loginForm' action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ) ?>?mod=gita_login&job=0" method=POST>
                    <!--<label class='w3-label' for='username'>$lanLoginUser</label>
                                                        <input class='w3-input' type=text id='username' name=UserName>-->

                    <label class='w3-label w3-center' for='password'>
                        <?php echo $lanLoginPass; ?>
                    </label>
                    <div class='w3-row w3-center'>
                        <div class=' w3-col m10'>
                                <input class='w3-input' type=password id='password' name='Password'>
                                <input class='w3-input' type=hidden id='username' name='UserName' >
                        </div>
                        <div class='w3-center w3-col m2' >
                            <i class='w3-button material-icons w3-large' class='submitButton'>perm_identity</i>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        staffCount= $("#StaffCount").text() -1 ;
        console.log(staffCount);
        CharSelect($(".characterPhoto:eq(" + characterCursor + ")").attr('id'));
        $(".characterPhoto:eq(" + characterCursor + ")").removeClass('w3-sepia');

        $(".characterPhoto").hover(function () {
            $(".characterPhoto").addClass('w3-sepia').removeClass('w3-lime w3-round');
            $(this).removeClass('w3-sepia').addClass('w3-lime w3-round');

        }, function () {
            $(this).addClass('w3-sepia').removeClass('w3-lime w3-round');
        });

        $(".characterPhoto").click(function () { CharSelect($(this).prop('id')); 
        $("#password").focus(); $(loginForm).removeAttr('hidden'); });

        $("html").keydown(function (c) {
            switch (c.which) {
                case 39:
                    characterCursor += 1;
                    break;
                case 37:
                    characterCursor -= 1;
                    break;
                case 38:
                    characterCursor -= 4;
                    break;
                case 40:
                    characterCursor += 4;
                    break;
                case 13:
                    c.preventDefault();
                    $(loginForm).removeAttr('hidden');
                    $("#password").focus();
                    break;
            }
            
            
            
            if (characterCursor < 0) { characterCursor += (staffCount+1); }
            if (characterCursor > staffCount) { characterCursor -= (staffCount+1); }
            CharSelect($(".characterPhoto:eq(" + characterCursor + ")").attr('id'));
    console.log(staffCount);
            console.log(characterCursor);
            console.log($(".characterPhoto:eq(" + characterCursor + ")").attr('id'));

            $(".characterPhoto").addClass('w3-sepia').removeClass('w3-lime w3-round');
            $(".characterPhoto:eq(" + characterCursor + ")").removeClass('w3-sepia').addClass('w3-lime w3-round');
        });

        $("#submit").click(function(){ $("#loginForm").submit();});
        $("#password").keydown(function(x){ if(x.which==13){$("#loginForm").submit();}});
       // $("#password").focus(function(){ $(loginForm).removeClass('w3-hide'); }).blur(function(){ $(loginForm).addClass('w3-hide') });;

    </script>
</body>

</html>
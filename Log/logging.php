<?
if(!empty($LogDes || $LogContent || $ErrorLog)){
    $kayu = new Kayu(); //Logging Engine Class
    $kayu-> KayuManis($LogDes,$LogContent,$ErrorLog);
}
?>
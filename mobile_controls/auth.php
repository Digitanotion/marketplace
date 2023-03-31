<?php
//Confirm if file is local or Public and add the right path
//session_start();
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
else if (strpos($url,'192.168')){
    require_once(__DIR__ . "\../vendor/autoload.php");
}else{
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}

use services\AccS\AccountManager;
use services\SecS\SecurityManager;


$security_ob = new SecurityManager();
$accManager_ob = new AccountManager();
$pageUsrID__ = (isset($_SESSION['gaijinmall_user_'])) ? $_SESSION['gaijinmall_user_'] : "none";

if (isset($_POST['_command_mobile']) && $_POST['_command_mobile']=="login_it_"){
    $_email_phone=$_POST['email_phone'];
$_password=$_POST['_password'];
echo json_encode($security_ob->auth__user($_email_phone,$_password));
}

if (isset($_POST['_command_mobile']) && $_POST['_command_mobile']=="register_it_"){
    $_phonenumber=$_POST['_phone_number'];
$_password=$_POST['_password'];
$_fname=$_POST['_f_name'];
$_lname=$_POST['_l_name'];
$_email=$_POST['_email'];//echo "password $_password | fname=$_fname | lname=$_lname | email=$_email | phone=$_phonenumber";
echo json_encode($accManager_ob->new_user_account ($_fname,$_lname,$_email,$_phonenumber,$_password));
}

if (isset($_POST['_command_mobile']) && $_POST['_command_mobile']=="register_it_auth2"){
    $_phonenumber=$_POST['_phone_number'];
$_password=$_POST['_password'];
$_fname=$_POST['_f_name'];
$_lname=$_POST['_l_name'];
$_photo=$_POST['_photo'];
$_email=$_POST['_email'];//echo "password $_password | fname=$_fname | lname=$_lname | email=$_email | phone=$_phonenumber";
echo json_encode($accManager_ob->new_user_account_auth($_fname,$_lname,$_email,$_phonenumber,$_password,$_photo,$_password));
}
?>
  
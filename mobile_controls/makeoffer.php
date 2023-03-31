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

use services\AdS\AdManager;
use services\AudS\AuditManager;
use services\MedS\MediaManager;
// use services\InitDB;
use services\AccS\AccountManager;
use services\MsgS\feedbackManager;
use services\SecS\SecurityManager;
use services\MsgS\messagingManager;

$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
/* 
PHP2Toast Send system message to toast listener
$sys_msg['msg_type']=1;
$sys_msg['msg']="Sign Successfull"; */
//Create an instance of security service to handle authentication
$securityManager_ob = new SecurityManager();
$adManager_ob = new AdManager();
$mediaManager = new MediaManager();
$audService_ob = new AuditManager();
$messaging_ob = new messagingManager();
$feedback_ob=new feedbackManager();
$accManager_ob=new AccountManager();

/* if (!$securityManager_ob->is_user_auth__()){
  header("location: Signin.php");
} */
$adID = "";
$pageUsrID__ = (isset($_POST['user_mob_id__']))?$_POST['user_mob_id__']: $_GET['user_mob_id__']; //$_SESSION['gaijinmall_user_'];
/*

$getCurrentUserInfo=$securityManager_ob->getUserInfoByID($usrID);
$getUsrInfo="";
if ($getUsrInfo['status']!=1){
  header("location: Signin.php");
}
else{
  $getUsrInfo=$getUsrInfo['message'];
}*/
$sys_msg = [];
$allAdByID = array();
$getUsrInfo = "";
$getUsrBizInfo="";
/* if (isset($_POST['reportAd__btn'])){
     $reportAdResponse=$feedback_ob->reportAd($pageUsrID__,$_POST['reportAd__Select'],$_POST['reportAdMsg__txt']);
    $sys_msg['msg_type'] = $reportAdResponse['status'];
    $sys_msg['msg'] = $reportAdResponse['message'];
   
} */
if (isset($_GET['adID']) || isset($_POST['adID'])) {
    $getID=$_POST['adID'];
  $adID = $securityManager_ob->sanitizeItem( $getID, "string");
  $allAdByID = $adManager_ob->getAdByID($adID);
  if ($allAdByID['status'] == "1") {
    $allAdByID = $allAdByID['message'];
    $getUsrInfo = $securityManager_ob->getUserInfoByID($allAdByID['mallUsrID']);
    $getUsrInfo = $getUsrInfo['message'];
  } else {
    //header("Location: ./");
  }
}

//SET AD VIEW
$adManager_ob->updateAdView($adID);
if (isset($_POST['makeOffer__btn'])) {
    $amount_offer=$_POST['amount_offer'];
    $reciever_id=$_POST['reciever_id'];
  if ($pageUsrID__ == "") {
    $sys_msg['msg_type'] = 500;
    $sys_msg['msg'] = "Login to make an offer";
  } else {
    echo json_encode($adManager_ob->usrMakeOffer($pageUsrID__, $adID, $amount_offer, $reciever_id));
   /*  $makeOffer_response = 
    
    $sys_msg['msg_type'] = $makeOffer_response['status'];
    $sys_msg['msg'] = $makeOffer_response['message']; */
  }
}
if (isset($_POST['sendChat__btn'])) {

    echo json_encode($messaging_ob->sendMsgUsrToUsr($pageUsrID__, $adID, $getUsrInfo['mallUsrID'], $_POST['sendChatMsg__txt'], "usr_to_usr"));
    /* $sys_msg['msg_type'] = $sendChat_response['status'];
    $sys_msg['msg'] = $sendChat_response['message']; */
  
}
if (isset($_POST['_command_mobile']) && $_POST['_command_mobile']=="login_it_"){
    $_email_phone=$_POST['email_phone'];
$_password=$_POST['_password'];
echo json_encode($security_ob->auth__user($_email_phone,$_password));
}

if (isset($_POST['reportAd__btn']) && isset($_POST['_command_mobile']) && $_POST['_command_mobile']=="reportAd_") {
    $reason_select=$_POST['reason_select'];
    $more_details=$_POST['more_details'];
  if ($pageUsrID__ == "") {
    $sys_msg['msg_type'] = 500;
    $sys_msg['msg'] = "Login to make an offer";
  } else {
    echo json_encode($feedback_ob->reportAd_mobile($pageUsrID__, $reason_select, $more_details,$adID));
   /*  $makeOffer_response = 
    $sys_msg['msg_type'] = $makeOffer_response['status'];
    $sys_msg['msg'] = $makeOffer_response['message']; */
  }
}

if (isset($_POST['blockUserAd__btn']) && isset($_POST['_command_mobile']) && $_POST['_command_mobile']=="blockUserAd_") {
 $sellerUsr_ID=$_POST['sellerUsr_ID'];
  echo json_encode($accManager_ob->blockUserAd_mobile($pageUsrID__, $adID, $sellerUsr_ID));

}

if (isset($_POST['_command_mobile']) && $_POST['_command_mobile']=="register_it_"){
    $_phonenumber=$_POST['_phone_number'];
$_password=$_POST['_password'];
$_fname=$_POST['_f_name'];
$_lname=$_POST['_l_name'];
$_email=$_POST['_email'];//echo "password $_password | fname=$_fname | lname=$_lname | email=$_email | phone=$_phonenumber";
echo json_encode($accManager_ob->new_user_account ($_fname,$_lname,$_email,$_phonenumber,$_password));
}
?>
  
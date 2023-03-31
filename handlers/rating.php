<?php
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url, 'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
}  else if (strpos($url,'192.168')){
    require_once(__DIR__ . "\../vendor/autoload.php");
} 
else {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
}



use services\AdS\AdManager;
use services\MedS\MediaManager;
use services\AccS\AccountManager;
use services\AudS\AuditManager;
use services\MsgS\feedbackManager;
use services\SecS\SecurityManager;

$securityManager_ob = new SecurityManager();
$adsManager_ob = new AdManager();
$usrAccManager_ob = new AccountManager();
$feedback_ob = new feedbackManager();
$mediaManager = new MediaManager();
$timeAgo_ob = new AuditManager();


    if (isset($_POST["rating_num"])) {
        
    	$rate = $feedback_ob->updateAdUsrRating($_POST["adID"], $_POST["userID"], $_POST["rating_num"]);
    	print_r($rate['status']);
    }






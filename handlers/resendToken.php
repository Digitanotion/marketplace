<?php
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
use services\SecS\SecurityManager;
use services\SecS\InputValidator;
use services\MedS\MediaManager;
use services\MsgS\messagingManager;

$mediaManager_ob = new MediaManager();
$adManager_ob = new AdManager();
$security_ob = new SecurityManager();
$inputValidate_ob = new InputValidator();
$message_ob=new messagingManager();
$pageUsrID__ = (isset($_SESSION['gaijinmall_user_'])) ? $_SESSION['gaijinmall_user_'] : "none";

echo $security_ob->generatePhoneVerifyToken($pageUsrID__,$_GET['userPhone'])['status'];
?>